<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    /**
     * Create Order
     */
    public function store(Request $request)
        {
            $request->validate([
        'user_id' => 'required|exists:users,id',

        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty' => 'required|integer|min:1',

        'payment_method' => 'required|string|in:cod,online',

        'shipping_first_name' => 'required|string|max:100',
        'shipping_last_name' => 'nullable|string|max:100',
        'shipping_phone' => 'required|string|max:20',
        'shipping_address1' => 'required|string|max:255',
        'shipping_address2' => 'nullable|string|max:255',

        'note' => 'nullable|string|max:500',
    ]);


        try {

            $order = DB::transaction(function () use ($request) {

                $totalQty = 0;
                $totalAmount = 0;


                /*
                |--------------------------------------------------------------------------
                | Create Order
                |--------------------------------------------------------------------------
                */

                $order = Order::create([
                    'user_id' => $request->user_id,

                    'total_qty' => 0,
                    'total_amount' => 0,
                    'wallet_used' => 0,

                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'status' => 'pending',

                    'shipping_first_name' => $request->shipping_first_name,
                    'shipping_last_name' => $request->shipping_last_name,
                    'shipping_phone' => $request->shipping_phone,
                    'shipping_address1' => $request->shipping_address1,
                    'shipping_address2' => $request->shipping_address2,

                    'note' => $request->note,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Order Items
                |--------------------------------------------------------------------------
                */

                foreach ($request->items as $item) {

                    $product = Product::lockForUpdate()
                        ->where('status', 1)
                        ->find($item['product_id']);


                    if (!$product) {

                        abort(
                            422,
                            'Product not available.'
                        );
                    }


                    $qty = (int) $item['qty'];


                    /*
                    |--------------------------------------------------------------------------
                    | Stock Check
                    |--------------------------------------------------------------------------
                    */

                    if ($product->stock < $qty) {

                        abort(
                            422,
                            $product->name .
                            ' has only ' .
                            $product->stock .
                            ' stock available.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Price
                    |--------------------------------------------------------------------------
                    */

                    $price = $product->getPrice($qty);

                    $itemTotal = $price * $qty;


                    $totalQty += $qty;

                    $totalAmount += $itemTotal;


                    /*
                    |--------------------------------------------------------------------------
                    | Create Item
                    |--------------------------------------------------------------------------
                    */

                    $order->items()->create([

                        'product_id' =>
                            $product->id,

                        'qty' =>
                            $qty,

                        'price' =>
                            $price,

                        'total' =>
                            $itemTotal,

                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Reduce Stock
                    |--------------------------------------------------------------------------
                    */

                    $product->decrement(
                        'stock',
                        $qty
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Update Order Total
                |--------------------------------------------------------------------------
                */

                $order->update([

                    'total_qty' =>
                        $totalQty,

                    'total_amount' =>
                        $totalAmount,

                ]);


                return $order;
            });


            /*
            |--------------------------------------------------------------------------
            | Load Items
            |--------------------------------------------------------------------------
            */

            $order->load([
                'items.product'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => true,

                'message' =>
                    'Order placed successfully.',

                'order' => [

                    'id' =>
                        $order->id,

                    'user_id' =>
                        $order->user_id,

                    'total_qty' =>
                        $order->total_qty,

                    'total_amount' =>
                        $order->total_amount,

                    'payment_method' =>
                        $order->payment_method,

                    'status' =>
                        $order->status,

                    'note' =>
                        $order->note,

                    'shipping' => [
                        'first_name' => $order->shipping_first_name,
                        'last_name' => $order->shipping_last_name,
                        'phone' => $order->shipping_phone,
                        'address1' => $order->shipping_address1,
                        'address2' => $order->shipping_address2,
                    ],

                    'items' =>
                        $order->items->map(
                            function ($item) {

                                return [

                                    'product_id' =>
                                        $item->product_id,

                                    'product_name' =>
                                        $item->product
                                            ? $item->product->name
                                            : null,

                                    'qty' =>
                                        $item->qty,

                                    'price' =>
                                        $item->price,

                                    'total' =>
                                        $item->total,

                                ];
                            }
                        )->values(),

                ],

            ], 201);


        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage(),

            ], 422);


        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Unable to place order.',

            ], 500);
        }
    }


    /**
     * Get User Orders
     */
    public function index($user_id)
    {
        $orders = Order::with([
            'items.product'
        ])
        ->where('user_id', $user_id)
        ->latest()
        ->get();


        return response()->json([

            'success' => true,

            'message' =>
                'Orders fetched successfully.',

            'orders' => $orders->map(
                function ($order) {

                    return [

                        'id' =>
                            $order->id,

                        'total_qty' =>
                            $order->total_qty,

                        'total_amount' =>
                            $order->total_amount,

                        'payment_method' =>
                            $order->payment_method,

                        'status' =>
                            $order->status,

                        'note' =>
                            $order->note,

                        'created_at' =>
                            $order->created_at,

                        'items' =>
                            $order->items->map(
                                function ($item) {

                                    return [

                                        'product_id' =>
                                            $item->product_id,

                                        'product_name' =>
                                            $item->product
                                                ? $item->product->name
                                                : null,

                                        'qty' =>
                                            $item->qty,

                                        'price' =>
                                            $item->price,

                                        'total' =>
                                            $item->total,

                                    ];
                                }
                            )->values(),

                    ];
                }
            )->values(),

        ], 200);
    }


    /**
     * Get Single Order
     */
    public function show(
        $user_id,
        $id
    ) {

        $order = Order::with([
            'items.product'
        ])
        ->where('user_id', $user_id)
        ->where('id', $id)
        ->first();


        if (!$order) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Order not found.',

            ], 404);
        }


        return response()->json([

            'success' => true,

            'message' =>
                'Order details fetched successfully.',

            'order' => [

                'id' =>
                    $order->id,

                'user_id' =>
                    $order->user_id,

                'total_qty' =>
                    $order->total_qty,

                'total_amount' =>
                    $order->total_amount,

                'payment_method' =>
                    $order->payment_method,

                'status' =>
                    $order->status,

                'note' =>
                    $order->note,

                'created_at' =>
                    $order->created_at,

                'items' =>
                    $order->items->map(
                        function ($item) {

                            return [

                                'product_id' =>
                                    $item->product_id,

                                'product_name' =>
                                    $item->product
                                        ? $item->product->name
                                        : null,

                                'qty' =>
                                    $item->qty,

                                'price' =>
                                    $item->price,

                                'total' =>
                                    $item->total,

                            ];
                        }
                    )->values(),

            ],

        ], 200);
    }
} 