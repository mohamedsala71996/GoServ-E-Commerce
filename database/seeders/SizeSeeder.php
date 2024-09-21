<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clothing Sizes
        $clothingSizes = [
            ['type' => 'clothing', 'size' => 'XS'],
            ['type' => 'clothing', 'size' => 'S'],
            ['type' => 'clothing', 'size' => 'M'],
            ['type' => 'clothing', 'size' => 'L'],
            ['type' => 'clothing', 'size' => 'XL'],
            ['type' => 'clothing', 'size' => 'XXL'],
            ['type' => 'clothing', 'size' => 'XXXL'],
            ['type' => 'clothing', 'size' => '4XL'],
            ['type' => 'clothing', 'size' => '5XL'],
        ];

        // Footwear Sizes
        $footwearSizes = [
            ['type' => 'footwear', 'size' => '35'],
            ['type' => 'footwear', 'size' => '36'],
            ['type' => 'footwear', 'size' => '37'],
            ['type' => 'footwear', 'size' => '38'],
            ['type' => 'footwear', 'size' => '39'],
            ['type' => 'footwear', 'size' => '40'],
            ['type' => 'footwear', 'size' => '41'],
            ['type' => 'footwear', 'size' => '42'],
            ['type' => 'footwear', 'size' => '43'],
            ['type' => 'footwear', 'size' => '44'],
            ['type' => 'footwear', 'size' => '45'],
            ['type' => 'footwear', 'size' => '46'],
            ['type' => 'footwear', 'size' => '47'],
        ];

        // Kids' Footwear Sizes
        $kidsFootwearSizes = [
            ['type' => 'kids_footwear', 'size' => '18'],
            ['type' => 'kids_footwear', 'size' => '19'],
            ['type' => 'kids_footwear', 'size' => '20'],
            ['type' => 'kids_footwear', 'size' => '21'],
            ['type' => 'kids_footwear', 'size' => '22'],
            ['type' => 'kids_footwear', 'size' => '23'],
            ['type' => 'kids_footwear', 'size' => '24'],
            ['type' => 'kids_footwear', 'size' => '25'],
            ['type' => 'kids_footwear', 'size' => '26'],
            ['type' => 'kids_footwear', 'size' => '27'],
            ['type' => 'kids_footwear', 'size' => '28'],
            ['type' => 'kids_footwear', 'size' => '29'],
            ['type' => 'kids_footwear', 'size' => '30'],
        ];

        // Accessories Sizes (e.g., belts, hats)
        $accessorySizes = [
            ['type' => 'accessory', 'size' => 'S'],
            ['type' => 'accessory', 'size' => 'M'],
            ['type' => 'accessory', 'size' => 'L'],
            ['type' => 'accessory', 'size' => 'XL'],
        ];

        // Bedding Sizes (e.g., bedsheets, pillowcases)
        $beddingSizes = [
            ['type' => 'bedding', 'size' => 'Single'],
            ['type' => 'bedding', 'size' => 'Double'],
            ['type' => 'bedding', 'size' => 'Queen'],
            ['type' => 'bedding', 'size' => 'King'],
        ];

        // Additional Categories
        $categories = [
            ['type' => 'sportswear', 'size' => 'XS'],
            ['type' => 'sportswear', 'size' => 'S'],
            ['type' => 'sportswear', 'size' => 'M'],
            ['type' => 'sportswear', 'size' => 'L'],
            ['type' => 'sportswear', 'size' => 'XL'],
            ['type' => 'sportswear', 'size' => 'XXL'],
        ];

        // Combine all sizes into one array
        $sizes = array_merge($clothingSizes, $footwearSizes, $kidsFootwearSizes, $accessorySizes, $beddingSizes, $categories);

        foreach ($sizes as $size) {
            DB::table('sizes')->updateOrInsert(
                ['type' => $size['type'], 'size' => $size['size']],
                ['type' => $size['type'], 'size' => $size['size']]
            );
        }
    }
}
