<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\City;
use App\Models\Coupon;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use App\Models\ShippingSetting;
use App\Models\ShippingWeight;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'carts' => 'required|array',
            'carts.*.cart_id' => 'required|exists:carts,id',
            // 'cart.*.product_color_id' => 'required|exists:product_colors,id',
            // 'cart.*.quantity' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string' // Validate coupon code if provided

        ]);



        $user = Auth::user();
        $cartItems = $request->input('carts');
        $totalAmount = 0;
        $totalWeight = 0;
        $couponDiscountAmount = 0;
        $totalCost = 0;

        // Calculate total amount
        foreach ($cartItems as $cartItem) {
            $cart=Cart::find($cartItem['cart_id']);
            $productColorSize = ProductColorSize::find($cart->product_color_size_id);
            $totalAmount += $productColorSize->price_after_discount *  $cart->quantity;
            $totalCost += $productColorSize->cost *  $cart->quantity;
            $totalWeight += $productColorSize->productColor->product->weight *  $cart->quantity;
        }
        $couponCode = $request->input('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();

            if ($coupon && $coupon->isValid()) {
                // Apply coupon discount
                $totalAmountWithDiscount = $coupon->applyDiscount($totalAmount);
                $couponDiscountAmount = $totalAmount - $totalAmountWithDiscount;
                $totalAmount = $totalAmountWithDiscount;
                // Increment coupon usage
                $coupon->incrementUsage();
            } else {
                return response()->json(['status' => 'error', 'message' => 'Invalid or expired coupon code'], 400);
            }
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'total_cost' => $totalCost,
            'total_weight' => $totalWeight,
            'coupon_discount' => $couponDiscountAmount, // Store discount amount
            'status' => 'pending',
            'tracking_number' => Str::uuid(),

        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            $cart=Cart::find($cartItem['cart_id']);
            $productColorSize = ProductColorSize::find($cart->product_color_size_id);
            OrderItem::create([
                'order_id' => $order->id,
                'product_color_size_id' => $productColorSize->id,
                'quantity' => $cart->quantity,
                'price' => $productColorSize->price_after_discount,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'order' => $order,
        ], 201);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'order_id' => 'required|exists:orders,id',
    //         'first_name' => 'required|string',
    //         'last_name' => 'required|string',
    //         'phone_number' => 'required|string',
    //         'country' => 'required|string',
    //         'city' => 'required|string',
    //         'state' => 'required|string',
    //         'address' => 'required|string',
    //     ]);

    //     $userDetail = UserDetail::updateOrCreate(
    //         ['order_id' => $request->input('order_id')],
    //         $request->all()
    //     );

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'User details saved successfully',
    //         'user_detail' => $userDetail
    //     ], 201);
    // }
    public function store(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'country' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'state' => 'required|string',
            'address' => 'required|string',
        ];

        // If there's no shipping setting, add custom validation for the city field
        $shippingSetting = ShippingSetting::first();
        // if (!isset($shippingSetting)) {
        //     $rules['city'] = [
        //         'required',
        //         'string',
        //         function ($attribute, $value, $fail) {
        //             // Check if the city exists in either 'en' or 'ar' fields
        //             $cityExists = City::where('name->en', $value)
        //                 ->orWhere('name->ar', $value)
        //                 ->exists();

        //             if (!$cityExists) {
        //                 $fail('The selected city is invalid.');
        //             }
        //         },
        //     ];
        // }

        $request->validate($rules);


        $order = Order::find($request->input('order_id'));
        $city_id = $request->input('city_id');

        // Find the city
        $cityModel = City::where('id',$city_id)->first();

        $shippingRate = $shippingSetting ? $shippingSetting->default_rate : $cityModel->shipping_rate;

        // Calculate additional shipping rate based on weight
        $totalWeight = $order->total_weight;
        $additionalShippingRate = 0;

        if ($totalWeight) {
            // Get the appropriate ShippingWeight rate based on the order's weight
            $shippingWeight = ShippingWeight::where('min_weight', '<=', $totalWeight)
                ->where('max_weight', '>=', $totalWeight)
                ->first();

            if ($shippingWeight) {
                $additionalShippingRate = $shippingWeight->additional_rate;
            }
        }

        // Final shipping rate is the sum of the base rate and the additional rate based on weight
        $finalShippingRate = $shippingRate + $additionalShippingRate;

        // Update the order with the calculated shipping rate
        $order->update([
            'total_amount' => $order->total_amount + $finalShippingRate,
            'shipping_amount' => $finalShippingRate
        ]);

        // Save or update user details
        $userDetail = UserDetail::updateOrCreate(
            ['order_id' => $request->input('order_id')],
            $request->all()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'User details saved successfully',
            'user_detail' => $userDetail
        ], 201);
    }


}
