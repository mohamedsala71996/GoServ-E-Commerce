<?php

namespace App\Http\Controllers\Api\Dashboard\Reports;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\ProductNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReportController extends Controller
{


    public function mostProfitableProducts(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Query to get the most profitable products
        $profitableProducts = DB::table('order_items')
            ->join('product_color_sizes', 'order_items.product_color_size_id', '=', 'product_color_sizes.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name')
            ->selectRaw('SUM(order_items.quantity * (product_color_sizes.price - product_color_sizes.cost)) as total_profit')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_profit')
            ->limit(10) // Limit to top 10 most profitable products
            ->get();

        // Format the data for response
        $data = $profitableProducts->map(function ($profitableProduct) {
            $product_name = Product::find($profitableProduct->id)->getTranslation('name', app()->getLocale());
            $product = Product::find($profitableProduct->id);
            return [
                'product_id' =>  $product->id,
                'product_name' => $product_name,
                'total_profit' => $profitableProduct->total_profit,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function abandonedCarts(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Retrieve carts based on the provided date range
        $carts = Cart::with(['user', 'productColorSize.productColor.product']) // Load user, product color, and product relationships
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $cartDetails = [];

        foreach ($carts as $cart) {
            $productColorSize = $cart->productColorSize;

            if ($productColorSize) {
                // Get the product details and user details
                $product = $productColorSize->productColor->product;
                $user = $cart->user;
                $price = $productColorSize->price;
                $quantity = $cart->quantity;
                $total = $price * $quantity;

                // Add the data to the array
                $cartDetails[] = [
                    'customer_name' => $user->name,
                    'product_name' => $product->getTranslation('name', app()->getLocale()),
                    'phone_number' => $user->phone,
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


    public function getNotifyProducts(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $ids = ProductNotification::whereBetween('created_at', [$startDate, $endDate])
            ->pluck('product_color_size_id');

        $productColorSizes = ProductColorSize::whereIn('id', $ids)
            ->with(['productColor.product'])
            ->get();

        $notify_products = $productColorSizes->map(function ($productColorSize) {
            $photos = json_decode($productColorSize->productColor->photos, true);
            $firstPhoto = $photos[0] ?? null;

            return [
                'product_name' => $productColorSize->productColor->product->getTranslation('name', app()->getLocale()),
                'size' => $productColorSize->size->size,
                'color' => $productColorSize->productColor->color->getTranslation('name', app()->getLocale()),
                'first_photo' => $firstPhoto,
            ];
        });

        return response()->json([
            'status' => 'success',
            'notify_products' => $notify_products,
        ]);
    }

    public function topSellingProducts(Request $request)
    {
        // Validate the date inputs
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $locale = app()->getLocale();

        // Fetch top selling products within the date range
        $products = Product::with(['category', 'productColors']) // Load relationships
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.id', 'products.name', 'products.category_id', DB::raw('COUNT(order_items.product_id) as sales_count')) // Select specific columns
            ->groupBy('products.id', 'products.name', 'products.category_id') // Group by selected columns
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();

        // Translate product names and fetch photos
        $products->each(function ($product) use ($locale) {
            // Fetch translated name for the product and its category
            $product->translated_name = $product->getTranslation('name', $locale);
            $product->category_name = $product->category->getTranslation('name', $locale);

            // Check if there are product colors and fetch the first photo if available
            if ($product->productColors->isNotEmpty()) {
                $photos = json_decode($product->productColors[0]->photos);
                $product->photo = $photos ? $photos[0] : null;
            } else {
                $product->photo = null; // No photo found
            }
        });

        return response()->json([
            'status' => 'success',
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->translated_name, // Use the translated name
                    'category' => $product->category_name, // Translated category name
                    'sales_count' => $product->sales_count,
                    'photo' => $product->photo, // First photo from the product color
                ];
            }),
        ]);
    }





}
