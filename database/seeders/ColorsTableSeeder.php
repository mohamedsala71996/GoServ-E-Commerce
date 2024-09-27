<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => json_encode(['en' => 'Red', 'ar' => 'أحمر']), 'hex_code' => '#FF0000'],
            ['name' => json_encode(['en' => 'Green', 'ar' => 'أخضر']), 'hex_code' => '#00FF00'],
            ['name' => json_encode(['en' => 'Blue', 'ar' => 'أزرق']), 'hex_code' => '#0000FF'],
            ['name' => json_encode(['en' => 'Yellow', 'ar' => 'أصفر']), 'hex_code' => '#FFFF00'],
            ['name' => json_encode(['en' => 'Black', 'ar' => 'أسود']), 'hex_code' => '#000000'],
            ['name' => json_encode(['en' => 'White', 'ar' => 'أبيض']), 'hex_code' => '#FFFFFF'],
            ['name' => json_encode(['en' => 'Purple', 'ar' => 'أرجواني']), 'hex_code' => '#800080'],
            ['name' => json_encode(['en' => 'Cyan', 'ar' => 'سماوي']), 'hex_code' => '#00FFFF'],
            ['name' => json_encode(['en' => 'Magenta', 'ar' => 'ماجنتا']), 'hex_code' => '#FF00FF'],
            ['name' => json_encode(['en' => 'Orange', 'ar' => 'برتقالي']), 'hex_code' => '#FFA500'],
            ['name' => json_encode(['en' => 'Pink', 'ar' => 'وردي']), 'hex_code' => '#FFC0CB'],
            ['name' => json_encode(['en' => 'Brown', 'ar' => 'بني']), 'hex_code' => '#A52A2A'],
            ['name' => json_encode(['en' => 'Gray', 'ar' => 'رمادي']), 'hex_code' => '#808080'],
            ['name' => json_encode(['en' => 'Light Blue', 'ar' => 'أزرق فاتح']), 'hex_code' => '#ADD8E6'],
            ['name' => json_encode(['en' => 'Dark Green', 'ar' => 'أخضر داكن']), 'hex_code' => '#006400'],
            ['name' => json_encode(['en' => 'Light Green', 'ar' => 'أخضر فاتح']), 'hex_code' => '#90EE90'],
            ['name' => json_encode(['en' => 'Olive', 'ar' => 'زيتوني']), 'hex_code' => '#808000'],
            ['name' => json_encode(['en' => 'Navy', 'ar' => 'كحلي']), 'hex_code' => '#000080'],
            ['name' => json_encode(['en' => 'Teal', 'ar' => 'فيروزي']), 'hex_code' => '#008080'],
            ['name' => json_encode(['en' => 'Lavender', 'ar' => 'لافندر']), 'hex_code' => '#E6E6FA'],
            ['name' => json_encode(['en' => 'Coral', 'ar' => 'مرجاني']), 'hex_code' => '#FF7F50'],
            ['name' => json_encode(['en' => 'Goldenrod', 'ar' => 'ذهبي']), 'hex_code' => '#DAA520'],
            // Add more colors as needed
        ];

        DB::table('colors')->insert($colors);
    }
}
