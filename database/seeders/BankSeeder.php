<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    public function run()
    {
        if (! Schema::hasTable('banks')) {
            $this->command->warn('banks table does not exist, skipping BankSeeder.');
            return;
        }

        $banks = [
            ['en' => 'Al Rajhi Bank', 'ar' => 'مصرف الراجحي', 'slug' => 'alrajhi'],
            ['en' => 'SNB (National Commercial Bank)', 'ar' => 'البنك الأهلي السعودي', 'slug' => 'snb'],
            ['en' => 'Riyad Bank', 'ar' => 'بنك الرياض', 'slug' => 'riyad'],
            ['en' => 'Banque Saudi Fransi', 'ar' => 'البنك السعودي الفرنسي', 'slug' => 'sfransi'],
            ['en' => 'SABB', 'ar' => 'ساب', 'slug' => 'sabb'],
            ['en' => 'Alinma Bank', 'ar' => 'مصرف الإنماء', 'slug' => 'alinma'],
            ['en' => 'Arab National Bank', 'ar' => 'البنك العربي الوطني', 'slug' => 'arab_national'],
            ['en' => 'SAIB', 'ar' => 'البنك السعودي للاستثمار', 'slug' => 'saib'],
            ['en' => 'Bank AlJazira', 'ar' => 'بنك الجزيرة', 'slug' => 'aljazira'],
            ['en' => 'Gulf International Bank', 'ar' => 'بنك الخليج الدولي', 'slug' => 'gib'],
        ];

        DB::transaction(function () use ($banks) {
            $order = 1;
            foreach ($banks as $b) {
                $name_en = $b['en'];
                $name_ar = $b['ar'];
                $logo = 'uploads/banks/' . $b['slug'] . '.png';

                \App\Models\Bank::updateOrCreate([
                    'name_en' => $name_en,
                ], [
                    'name_en' => $name_en,
                    'name_ar' => $name_ar,
                    'logo' => $logo,
                    'status' => 'active',
                    'sort_order' => $order,
                ]);

                $order++;
            }
        });

        $this->command->info('BankSeeder completed.');
    }
}
