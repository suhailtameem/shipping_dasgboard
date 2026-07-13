<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTypesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('expense_types')->insertOrIgnore([
            ['id' => 1,  'name_en' => 'Shipping Cost',  'name_ar' => 'تكلفة الشحن',              'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'name_en' => 'Customs Duty',   'name_ar' => 'الرسوم الجمركية',          'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'name_en' => 'VAT',            'name_ar' => 'ضريبة القيمة المضافة',     'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'name_en' => 'Insurance Fee',  'name_ar' => 'رسوم التأمين',             'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'name_en' => 'Packaging Fee',  'name_ar' => 'رسوم التغليف',             'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'name_en' => 'Handling Fee',   'name_ar' => 'رسوم المناولة',            'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'name_en' => 'Storage Fee',    'name_ar' => 'رسوم التخزين',             'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'name_en' => 'Delivery Fee',   'name_ar' => 'رسوم التوصيل',             'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'name_en' => 'Fuel Surcharge', 'name_ar' => 'رسوم الوقود الإضافية',    'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name_en' => 'Other Expenses', 'name_ar' => 'مصروفات أخرى',            'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
