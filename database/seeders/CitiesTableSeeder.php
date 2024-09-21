<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Seed the cities table.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['name' => json_encode(['en' => 'Riyadh', 'ar' => 'الرياض']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Jeddah', 'ar' => 'جدة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Dammam', 'ar' => 'الدمام']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Khobar', 'ar' => 'الخبر']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Mecca', 'ar' => 'مكة المكرمة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Medina', 'ar' => 'المدينة المنورة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Buraidah', 'ar' => 'بريدة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Tabuk', 'ar' => 'تبوك']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Najran', 'ar' => 'نجران']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Hail', 'ar' => 'حائل']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Abha', 'ar' => 'أبها']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Khamis Mushait', 'ar' => 'خميس مشيط']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Jizan', 'ar' => 'جازان']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Arar', 'ar' => 'عرعر']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Sakaka', 'ar' => 'سكاكا']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Jouf', 'ar' => 'الجوف']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Qassim', 'ar' => 'القصيم']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Jubail', 'ar' => 'الجبيل']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Khobar', 'ar' => 'الخبر']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Dhahran', 'ar' => 'الظهران']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Bahah', 'ar' => 'الباحة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Kharj', 'ar' => 'الخارج']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Mubarraz', 'ar' => 'المبرز']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Dawadmi', 'ar' => 'الدوادمي']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Hafr Al Batin', 'ar' => 'حفر الباطن']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Bukayriyah', 'ar' => 'البكيرية']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Sulf', 'ar' => 'الصفا']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Quwaiyyah', 'ar' => 'القويعية']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Mukaylah', 'ar' => 'المكيلة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Udhailiyah', 'ar' => 'الذيلية']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Abu Samrah', 'ar' => 'أبو سمرة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Tanomah', 'ar' => 'تنومة']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Dammam', 'ar' => 'الدمام']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Wajh', 'ar' => 'الوجه']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Wadi Al Dawasir', 'ar' => 'وادي الدواسر']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Mithnab', 'ar' => 'المذنب']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Khobar', 'ar' => 'الخبر']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Al Shaqra', 'ar' => 'الشقراء']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Aflaj', 'ar' => 'الافلاج']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Hail', 'ar' => 'حائل']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Arar', 'ar' => 'عرعر']), 'shipping_rate' => 0],
            ['name' => json_encode(['en' => 'Arafat', 'ar' => 'عرفات']), 'shipping_rate' => 0],
        ];

        DB::table('cities')->insert($cities);
    }
}
