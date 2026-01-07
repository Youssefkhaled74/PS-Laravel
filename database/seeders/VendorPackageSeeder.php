<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendorPackage;

class VendorPackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            ['key' => 'primary', 'name' => ['en' => 'Primary', 'ar' => 'أساسي'], 'monthly' => 9900, 'yearly' => 118800, 'sort' => 0],
            ['key' => 'basic', 'name' => ['en' => 'Basic', 'ar' => 'أساسي 2'], 'monthly' => 29900, 'yearly' => 358800, 'sort' => 1],
            ['key' => 'advanced', 'name' => ['en' => 'Advanced', 'ar' => 'متقدم'], 'monthly' => 39900, 'yearly' => 478800, 'sort' => 2],
            ['key' => 'professional', 'name' => ['en' => 'Professional', 'ar' => 'محترف'], 'monthly' => 199900, 'yearly' => 2398800, 'sort' => 3],
        ];

        foreach ($packages as $p) {
            VendorPackage::updateOrCreate(
                ['key' => $p['key']],
                [
                    'name' => $p['name'],
                    'monthly_price' => $p['monthly'],
                    'yearly_price' => $p['yearly'],
                    'currency' => 'SAR',
                    'sort_order' => $p['sort'],
                    'status' => VendorPackage::STATUS_ACTIVE,
                ]
            );
        }
    }
}
