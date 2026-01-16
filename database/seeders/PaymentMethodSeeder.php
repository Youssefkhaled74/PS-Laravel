<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            [
                'key' => 'apple_pay',
                'name' => [
                    'en' => 'Apple Pay',
                    'ar' => 'آبل باي',
                ],
                'sort' => 0,
            ],
            [
                'key' => 'mada',
                'name' => [
                    'en' => 'Mada',
                    'ar' => 'مدى',
                ],
                'sort' => 1,
            ],
            [
                'key' => 'tamara',
                'name' => [
                    'en' => 'Tamara',
                    'ar' => 'تمارا',
                ],
                'sort' => 2,
            ],
            [
                'key' => 'tabby',
                'name' => [
                    'en' => 'Tabby',
                    'ar' => 'تابي',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'paymob',
                'name' => [
                    'en' => 'Paymob',
                    'ar' => 'بايموب',
                ],
                'sort' => 4,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['key' => $method['key']],
                [
                    'name' => $method['name'],
                    'status' => 'active',
                    'sort_order' => $method['sort'],
                ]
            );
        }
    }
}
