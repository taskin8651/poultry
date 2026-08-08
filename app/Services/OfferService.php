<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class OfferService
{
    protected WalletService $wallet;

    public function __construct(WalletService $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Evaluate every active offer against a set of purchase lines and
     * return the ones that qualify, with the cashback amount each earns.
     *
     * @param  Collection<int, array{product: Product, qty: int, price: float}>  $lines
     * @return array<int, array{offer: Offer, reward: float, measure: float}>
     */
    public function evaluate(Collection $lines): array
    {
        $results = [];

        foreach (Offer::active()->get() as $offer) {
            $qualifying = $lines->filter(fn ($line) => $offer->appliesToType($line['product']->type));

            if ($qualifying->isEmpty()) {
                continue;
            }

            $measure = match ($offer->condition_type) {
                'price' => $qualifying->sum(fn ($line) => $line['qty'] * $line['price']),
                'kg'    => $qualifying->filter(fn ($line) => $line['product']->sale_type === 'weight')->sum('qty'),
                'piece' => $qualifying->filter(fn ($line) => $line['product']->sale_type === 'piece')->sum('qty'),
                'qty'   => $qualifying->sum('qty'),
                default => 0,
            };

            if ($measure < (float) $offer->condition_value) {
                continue;
            }

            $qualifyingAmount = $qualifying->sum(fn ($line) => $line['qty'] * $line['price']);

            $reward = $offer->reward_kind === 'percent'
                ? round($qualifyingAmount * ((float) $offer->reward_value / 100), 2)
                : (float) $offer->reward_value;

            if ($reward <= 0) {
                continue;
            }

            $results[] = [
                'offer'   => $offer,
                'reward'  => $reward,
                'measure' => $measure,
            ];
        }

        return $results;
    }

    /**
     * Build evaluation lines from a session cart array (product_id, quantity, unit_price).
     */
    public function linesFromCart(array $cart): Collection
    {
        return collect($cart)
            ->map(function ($item) {
                $product = Product::find($item['product_id']);

                return $product ? [
                    'product' => $product,
                    'qty'     => (int) $item['quantity'],
                    'price'   => (float) $item['unit_price'],
                ] : null;
            })
            ->filter()
            ->values();
    }

    /**
     * Preview which offers the current cart would earn, without crediting anything.
     */
    public function previewForCart(array $cart): array
    {
        return $this->evaluate($this->linesFromCart($cart));
    }

    /**
     * Credit the customer's wallet for every offer their placed order qualifies for.
     */
    public function applyToOrder(Order $order, User $user): array
    {
        $order->loadMissing('items.product');

        $lines = $order->items->map(fn ($item) => [
            'product' => $item->product,
            'qty'     => (int) $item->qty,
            'price'   => (float) $item->price,
        ])->filter(fn ($line) => $line['product'] !== null)->values();

        $applied = [];

        foreach ($this->evaluate($lines) as $entry) {
            $this->wallet->credit(
                $user,
                $entry['reward'],
                "Offer cashback: {$entry['offer']->title} (Order #{$order->id})",
                'offer',
                $order->id
            );

            $applied[] = $entry;
        }

        return $applied;
    }
}
