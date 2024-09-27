<?php

namespace App\Http\Controllers\Api\Dashboard\Reports;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTax;
use App\Models\PaymobFee;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\ReturnSetting;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function totalCompletedOrdersAmount(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get all completed orders (status: accepted, out for delivery, delivered)
        $completedOrders = Order::whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();


        // Total completed order amount
        $totalAmount = $completedOrders->sum('total_amount');
        $coupon_discount_amount = $completedOrders->sum('coupon_discount');
        $totalAmountWithoutCoupons = $coupon_discount_amount + $totalAmount;
        $totalCost = $completedOrders->sum('total_cost');
        $shipping_amount = $completedOrders->sum('shipping_amount');

        // Calculate tax amount if tax rate exists
        $tax = OrderTax::first();
        $tax_amount = 0;
        if ($tax) {
            foreach ($completedOrders as $order) {
                $tax_amount += $order->total_amount * ($tax->rate / 100);
            }
        }

        // Calculate Paymob fees based on card type
        $paymob_fees = 0;
        foreach ($completedOrders as $order) {
            $paymob_fee = PaymobFee::where('card_type', $order->source_data_sub_type)->first();
            if ($paymob_fee) {
                $paymob_fees += ($order->total_amount * ($paymob_fee->percentage_fee / 100)) + $paymob_fee->fixed_fee;
            }
        }

        // Calculate net sales
        $net_sales = $totalAmount - $totalCost - $shipping_amount - $tax_amount - $paymob_fees;

        // Calculate return rate

        return response()->json([
            'status' => 'success',
            'total_amount_with_coupons' => $totalAmount,
            'coupon_discount_amount' => $coupon_discount_amount,
            'total_amount_without_coupons' => $totalAmountWithoutCoupons,
            'total_cost' => $totalCost,
            'shipping_amount' => $shipping_amount,
            'tax_amount' => $tax_amount,
            'paymob_fees' => $paymob_fees,
            'net_sales' => $net_sales,
            // 'return_rate' => $return_rate . '%',
            // 'total_completed_orders' => $totalOrdersCount,
            // 'returned_orders' => $returnedOrdersCount,
        ], 200);
    }


    public function returnRate(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $allOrders = Order::whereNotIn('status', ['pending', 'paid', 'failed', 'cancelled']) //paid not accepted yet
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();

        $returnedOrders = Order::whereIn('status', ['returned', 'out for delivery return', 'delivered return', 'not received'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();
        $totalOrdersCount = $allOrders->count();
        $returnedOrdersCount = $returnedOrders->count();
        $return_rate = $totalOrdersCount > 0 ? ($returnedOrdersCount / $totalOrdersCount) * 100 : 0;

        return response()->json([
            'status' => 'success',
            'return_rate' => $return_rate . '%',
        ], 200);
    }

    public function repeatPurchaseRate(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $totalCustomers = Order::whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->whereBetween('updated_at', [$startDate, $endDate])->distinct('user_id')->count('user_id');

        $repeatCustomers = Order::whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->whereBetween('updated_at', [$startDate, $endDate])->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $repeatPurchaseRate = $totalCustomers > 0
            ? ($repeatCustomers / $totalCustomers) * 100
            : 0;

        return response()->json([
            'status' => 'success',
            'repeat_purchase_rate' => $repeatPurchaseRate . '%',
        ]);
    }
    public function cartsAverage(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Retrieve carts based on the provided date range
        $carts = Cart::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalAmount = 0;
        $totalCarts = $carts->count();

        foreach ($carts as $cart) {
            $product = ProductColorSize::find($cart->product_color_size_id);

            if ($product) {
                $price = $product->price;
                $quantity = $cart->quantity;
                $totalAmount += $price * $quantity;
            }
        }

        // Calculate average cart value
        $averageCartValue = $totalCarts > 0 ? $totalAmount / $totalCarts : 0;

        return response()->json([
            'status' => 'success',
            'average_cart_value' => $averageCartValue,
        ]);
    }

    public function productSalesReport(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Query to get the total sales for each product
        $productSales = OrderItem::select('product_color_size_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(price * quantity) as total_sales')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('product_color_size_id')
            ->whereIn('orders.status', ['accepted', 'out for delivery', 'delivered']) // Add status filter
            ->with('productColorSize.productColor.product') // Load relationships
            ->get();

        // Format the data for response
        $salesData = $productSales->map(function ($sale) {
            // Access photos and decode them
            $photos = json_decode($sale->productColorSize->productColor->photos, true);
            $firstPhoto = $photos[0] ?? null;

            return [
                'product_id' => $sale->productColorSize->productColor->product->id,
                'product_name' => $sale->productColorSize->productColor->product->getTranslation('name', app()->getLocale()),
                'product_color' => $sale->productColorSize->productColor->color->getTranslation('name', app()->getLocale()),
                'product_color_size' => $sale->productColorSize->size->size,
                'total_quantity' => $sale->total_quantity,
                'total_sales' => $sale->total_sales,
                'photo' => $firstPhoto,
            ];
        });

        // Sort the sales data by product name after retrieving the data
        $sortedSalesData = $salesData->sortBy('product_id')->values();

        return response()->json([
            'status' => 'success',
            'data' => $sortedSalesData,
        ], 200);
    }

    public function productSalesWithoutSize(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Query to get the total sales for each product
          $productSales = OrderItem::select('product_id')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total_sales')
            // ->join('product_color_sizes', 'order_items.product_color_size_id', '=', 'product_color_sizes.id')
            // ->join('product_colors', 'product_color_sizes.product_color_id', '=', 'product_colors.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['accepted', 'out for delivery', 'delivered']) // Add status filter
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->with('product') // Load relationships
            ->get();

        // Format the data for response
        $salesData = $productSales->map(function ($sale) {
            $product_name = Product::find($sale->product_id)->getTranslation('name', app()->getLocale());
            $product = Product::find($sale->product_id);
            $photos = json_decode( $product->productColors[0]->photos, true);
            $firstPhoto = $photos[0] ?? null;

            return [
                'product_id' => $sale->product_id,
                'product_name' => $product_name,
                'total_quantity' => $sale->total_quantity,
                'total_sales' => $sale->total_sales,
                'photo' => $firstPhoto,
            ];
        });

        // Sort the sales data by product name after retrieving the data
        $sortedSalesData = $salesData->sortBy('product_id')->values();

        return response()->json([
            'status' => 'success',
            'data' => $sortedSalesData,
        ], 200);
    }


    public function categorySalesReport(Request $request)
    {
        // Get start_date and end_date from request parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate the input dates
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Query to get total sales for each category
        $categorySales = OrderItem::select('products.category_id')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total_sales')
            // ->join('product_color_sizes', 'order_items.product_color_size_id', '=', 'product_color_sizes.id')
            // ->join('product_colors', 'product_color_sizes.product_color_id', '=', 'product_colors.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['accepted', 'out for delivery', 'delivered']) // Add status filter
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.category_id')
            ->with('productColorSize.productColor.product.category') // Load relationships
            ->get();

        // Format the data for response
        $salesData = $categorySales->map(function ($sale) {
            $category_name = Category::find($sale->category_id)->getTranslation('name', app()->getLocale());
            return [
                'category_id' => $sale->category_id,
                'category_name' => $category_name,
                'total_quantity' => $sale->total_quantity,
                'total_sales' => $sale->total_sales,
            ];
        });

        // Sort by category name
        $sortedSalesData = $salesData->sortBy('category_name')->values();

        return response()->json([
            'status' => 'success',
            'data' => $sortedSalesData,
        ], 200);
    }


    public function brandSalesReport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ]);

    $brandSales = OrderItem::select('products.brand_id')
        ->selectRaw('SUM(order_items.quantity) as total_quantity')
        ->selectRaw('SUM(order_items.price * order_items.quantity) as total_sales')
        // ->join('product_color_sizes', 'order_items.product_color_size_id', '=', 'product_color_sizes.id')
        // ->join('product_colors', 'product_color_sizes.product_color_id', '=', 'product_colors.id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereIn('orders.status', ['accepted', 'out for delivery', 'delivered']) // Add status filter
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->groupBy('products.brand_id')
        ->get();

    $salesData = $brandSales->map(function ($sale) {
        $brand_name = Brand::find($sale->brand_id)->getTranslation('name', app()->getLocale());

            return [
                'brand_id' => $sale->brand_id,
                'brand_name' =>  $brand_name,
                'total_quantity' => $sale->total_quantity,
                'total_sales' => $sale->total_sales,
            ];
    });

    $sortedSalesData = $salesData->sortBy('brand_name')->values();
    return response()->json([
        'status' => 'success',
        'data' => $sortedSalesData,
    ], 200);
}


public function citySalesReport(Request $request)
{
    // Get start_date and end_date from request parameters
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Validate the input dates
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ]);

    // Query to get total sales for each city
    $citySales = OrderItem::select('user_details.city_id')
        ->selectRaw('SUM(order_items.quantity) as total_quantity')
        ->selectRaw('SUM(order_items.price * order_items.quantity) as total_sales')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('user_details', 'orders.id', '=', 'user_details.order_id')
        ->join('cities', 'user_details.city_id', '=', 'cities.id')
        ->whereIn('orders.status', ['accepted', 'out for delivery', 'delivered']) // Add status filter
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->groupBy('user_details.city_id')
        ->with('order.userDetail.city') // Load related city
        ->get();

    // Format the data for response
    $salesData = $citySales->map(function ($sale) {
        $city_name = City::find($sale->city_id)->getTranslation('name', app()->getLocale());
        return [
            'city_id' => $sale->city_id,
            'city_name' => $city_name,
            'total_quantity' => $sale->total_quantity,
            'total_sales' => $sale->total_sales,
        ];
    });

    // Sort by city name
    $sortedSalesData = $salesData->sortBy('city_name')->values();

    return response()->json([
        'status' => 'success',
        'data' => $sortedSalesData,
    ], 200);
}
}
