<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Referral;
use App\Models\Setting;
use App\Services\OfferService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected WalletService $wallet;
    protected OfferService $offers;

    public function __construct(WalletService $wallet, OfferService $offers)
    {
        $this->middleware('auth'); // must be logged in
        $this->wallet = $wallet;
        $this->offers = $offers;
    }

    // -------------------------------------------------------------------------
    // GET /checkout
    // -------------------------------------------------------------------------
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user           = Auth::user();
        $summary        = $this->calcSummary($cart);
        $offerPreview   = $this->offers->previewForCart($cart);

        return view('custom.checkout', compact('cart', 'user', 'summary', 'offerPreview'));
    }

    // -------------------------------------------------------------------------
    // POST /checkout
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'note'       => 'nullable|string|max:1000',
            'use_wallet' => 'nullable|boolean',
        ]);

        $user    = Auth::user();
        $summary = $this->calcSummary($cart);

        $walletToApply = 0;
        if ($request->boolean('use_wallet') && $user->wallet_balance > 0) {
            $walletToApply = round(min($user->wallet_balance, $summary['total']), 2);
        }

        DB::beginTransaction();
        try {
            // Lock and re-verify stock at the moment of purchase — the cart may be stale
            $products = Product::whereIn('id', collect($cart)->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cart as $item) {
                $product = $products->get($item['product_id']);

                if (! $product || $item['quantity'] > $product->stock) {
                    $available = $product->stock ?? 0;
                    throw new \RuntimeException(
                        "Sorry, only {$available} {$item['sale_type']} of \"{$item['name']}\" left in stock. Please update your cart."
                    );
                }
            }

            // Create order
            $order = Order::create([
                'user_id'        => $user->id,
                'total_qty'      => collect($cart)->sum('quantity'),
                'total_amount'   => $summary['total'],
                'wallet_used'    => $walletToApply,
                'payment_method' => 'cod',
                'status'         => 'pending',
                'note'           => $request->note,
            ]);

            // Create order items + decrement stock
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'qty'        => $item['quantity'],
                    'price'      => $item['unit_price'],  // bulk-resolved price
                ]);

                $products->get($item['product_id'])->decrement('stock', $item['quantity']);
            }

            // Deduct applied wallet balance
            if ($walletToApply > 0) {
                $this->wallet->debit(
                    $user,
                    $walletToApply,
                    "Used on order #{$order->id}",
                    'order',
                    $order->id
                );
            }

            // Reward the referrer on the referred user's very first order
            $this->rewardReferrerOnFirstOrder($user, $order);

            // Credit any active offers this order qualifies for, straight to the wallet
            $this->offers->applyToOrder($order, $user);

            DB::commit();

            // Clear cart after successful order
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->id)
                             ->with('success', 'Order placed successfully!');

        } catch (\RuntimeException $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    // -------------------------------------------------------------------------
    // Credits the referrer's wallet the first time a referred user orders.
    // -------------------------------------------------------------------------
    private function rewardReferrerOnFirstOrder($user, Order $order): void
    {
        if (! $user->referred_by) {
            return;
        }

        $isFirstOrder = Order::where('user_id', $user->id)->count() === 1;

        if (! $isFirstOrder) {
            return;
        }

        $referral = Referral::where('referred_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (! $referral) {
            return;
        }

        $rewardAmount = (float) (Setting::first()->referral_reward_amount ?? 0);

        if ($rewardAmount <= 0) {
            return;
        }

        $referrer = $referral->referrer;

        if (! $referrer) {
            return;
        }

        $this->wallet->credit(
            $referrer,
            $rewardAmount,
            "Referral bonus for inviting {$user->name}",
            'referral',
            $referral->id
        );

        $referral->update([
            'status'        => 'rewarded',
            'reward_amount' => $rewardAmount,
            'order_id'      => $order->id,
            'rewarded_at'   => now(),
        ]);
    }

    // -------------------------------------------------------------------------
    // HELPER
    // -------------------------------------------------------------------------
    private function calcSummary(array $cart): array
    {
        $subTotal = round(collect($cart)->sum('subtotal'), 2);

        return [
            'sub_total'  => $subTotal,
            'total_qty'  => collect($cart)->sum('quantity'),
            'total'      => $subTotal,
        ];
    }


    public function success(Order $order)
{
    abort_if($order->user_id !== Auth::id(), 403);

    $offerCashback = \App\Models\WalletTransaction::where('user_id', $order->user_id)
        ->where('reference_type', 'offer')
        ->where('reference_id', $order->id)
        ->get();

    return view('custom.checkout-success', compact('order', 'offerCashback'));
}
}
