<?php

namespace App\Http\Controllers\Api\Dashboard\Carts;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Mail\CartReminderMail;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CartAnalyticsController extends Controller
{
    public function abandonedCarts()
    {


        // Retrieve carts based on the provided date range
        $carts = Cart::with(['user', 'productColorSize.productColor.product'])
        ->latest()
        ->get();

        $cartDetails = [];

        foreach ($carts as $cart) {
            $productColorSize = $cart->productColorSize;

            if ($productColorSize) {
                // Get the product details and user details
                $product = $productColorSize->productColor->product;
                $photos = $productColorSize->productColor->photos;
               $photo=json_decode($photos);
                $user = $cart->user;
                $price = $productColorSize->price;
                $quantity = $cart->quantity;
                $total = $price * $quantity;

                // Add the data to the array
                $cartDetails[] = [
                    'customer_name' => $user->name,
                    'phone_number' => $user->phone,
                    'email' => $user->email,
                    'product_name' => $product->getTranslation('name', app()->getLocale()),
                    'photo' =>  $photo[0],
                    'product_price' => $price,
                    'cart_abandoned_date' => $cart->updated_at->toDateString(),
                    'product_quantity' => $quantity,
                    'total' => $total,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'cart_details' => $cartDetails, // Return cart details with the response
        ]);
    }
    public function remindCart($id)
    {
        // Find the cart with the associated relations
        $cart = Cart::with(['user', 'productColorSize.productColor.product'])->findOrFail($id);

        // Ensure that the product color size exists
        $productColorSize = $cart->productColorSize;

        if ($productColorSize) {
            // Get the product, user, and pricing details
            $product = $productColorSize->productColor->product;
            $photos = $productColorSize->productColor->photos;
            $photo = json_decode($photos);
            $user = $cart->user;
            $price = $productColorSize->price;
            $quantity = $cart->quantity;
            $total = $price * $quantity;

            // Create the cartDetails as a single associative array
            $cartDetails = [
                'customer_name' => $user->name,
                'phone_number' => $user->phone,
                'email' => $user->email,
                'product_name' => $product->getTranslation('name', app()->getLocale()),
                'photo' => $photo[0],
                'product_price' => $price,
                'cart_abandoned_date' => $cart->updated_at->toDateString(),
                'product_quantity' => $quantity,
                'total' => $total,
            ];

            // Send the reminder email
            Mail::to($user->email)->send(new CartReminderMail($cartDetails));
            $cart->reminder=1;
            $cart->save();

            return response()->json([
                'status' => 'success',
                'cart_details' => $cartDetails, // Return the cart details
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Product color size not found.'
        ], 404);
    }





}
