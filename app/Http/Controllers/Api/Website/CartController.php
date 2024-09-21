<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(StoreCartRequest $request)
    {
        $validated = $request->validated();
        $product = ProductColorSize::find($validated['product_color_size_id']);
        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
            $quantity=ProductColorSize::find($request->product_color_size_id)->quantity;

        // Check if the requested quantity is available
        if ($validated['quantity'] > $quantity) {
            return response()->json(['message' => 'Requested quantity exceeds available stock.'], 400);
        }
        // Check if the product already exists in the cart for the user
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_color_size_id', $validated['product_color_size_id'])
            ->first();

        if ($cartItem) {
            // If it exists, update the quantity
            $newQuantity = $cartItem->quantity + $validated['quantity'];

            // Check if the new quantity exceeds available stock
            if ($newQuantity > $quantity) {
                return response()->json(['message' => 'Requested quantity exceeds available stock.'], 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Otherwise, create a new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_color_size_id' =>$request->product_color_size_id,
                'quantity' => $validated['quantity'],
            ]);
        }
        return response()->json(['message' => 'Product added to cart successfully.']);
    }

    /**
     * View the cart items for the authenticated user.
     */
    public function view()
    {
        $cartItems = Cart::with('productColorSize')
            ->where('user_id', Auth::id())
            ->get();

        $totalAmount = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * $item->productColorSize->price_after_discount);
        }, 0);
        $totalWeight = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * $item->productColorSize->productColor->product->weight);
        }, 0);

        return response()->json([
            'total_amount' => $totalAmount,
            'total_weight' => $totalWeight,
            'cart_items' => CartResource::collection($cartItems),
        ]);
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $cartItem = Cart::findOrFail($id);
        $product = ProductColorSize::find( $cartItem->productColorSize->id);
        if ($validated['quantity'] > $product->quantity) {
            return response()->json(['message' => 'Requested quantity exceeds available stock.'], 400);
        }
        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return response()->json(['message' => 'Cart item updated successfully.']);
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart successfully.']);
    }
}
