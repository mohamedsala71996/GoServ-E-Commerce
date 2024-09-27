<?php

namespace App\Http\Controllers\Api\Dashboard\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SearchWordLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostSearchedController extends Controller
{

    public function mostSearchedProducts(Request $request)
{
    // Validate the date inputs if needed
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query to get the count of searches per product
    $query = Product::join('search_logs', 'products.id', '=', 'search_logs.product_id')
        ->select('products.id', 'products.name', DB::raw('COUNT(search_logs.product_id) as search_count'))
        ->groupBy('products.id', 'products.name')
        ->orderBy('search_count', 'desc');

    // Apply date filter if provided
    if ($startDate && $endDate) {
        $query->whereBetween('search_logs.created_at', [$startDate, $endDate]);
    }
    $locale = app()->getLocale();

    // Limit to top 10 searched products
    $mostSearchedProducts = $query->take(10)->get();

    $mostSearchedProducts->each(function ($product) use ($locale) {
        // Fetch translated name for the product and its category
        $product->translated_name = $product->getTranslation('name', $locale);
    });
    return response()->json([
        'status' => 'success',
        'products' =>$mostSearchedProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->translated_name,
                'sales_count' => $product->search_count,
            ];
        }),
    ]);
}

public function mostSearchedWords(Request $request)
{
    // Validate the date inputs if needed
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query to get the count of searches per product
    $query = SearchWordLog::select('search_word_logs.search_term', DB::raw('COUNT(*) as search_word_count'))
        ->groupBy('search_word_logs.search_term')
        ->orderBy('search_word_count', 'desc');

    // Apply date filter if provided
    if ($startDate && $endDate) {
        $query->whereBetween('search_word_logs.created_at', [$startDate, $endDate]);
    }

    // Limit to top 10 searched products
    $mostSearchedWords = $query->take(10)->get();

    return response()->json([
        'status' => 'success',
        'mostSearchedWords' => $mostSearchedWords
    ]);
}





}
