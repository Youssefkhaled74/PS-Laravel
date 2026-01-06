<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $samples = [
            ['name_en' => 'Acme', 'name_ar' => 'أكمي'],
            ['name_en' => 'Nova', 'name_ar' => 'نوفا'],
            ['name_en' => 'Sahara', 'name_ar' => 'صحارى'],
            ['name_en' => 'Atlas', 'name_ar' => 'أطلس'],
            ['name_en' => 'Lumen', 'name_ar' => 'لومين'],
            ['name_en' => 'Orion', 'name_ar' => 'أوريون'],
        ];

        // ensure uploads dir exists
        $uploadsDir = public_path('uploads/brands');
        if (! is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        $placeholder = public_path('images/brand-placeholder.png');
        foreach ($samples as $s) {
            $brand = Brand::firstOrCreate([
                'name_en' => $s['name_en']
            ], [
                'name_ar' => $s['name_ar'],
                'status' => Brand::STATUS_ACTIVE,
                'sort_order' => 0,
            ]);

            // copy placeholder into uploads/brands for this brand if logo not exist
            if (! $brand->logo) {
                $dest = 'uploads/brands/brand_' . $brand->id . '.png';
                $fullDest = public_path($dest);
                if (file_exists($placeholder) && ! file_exists($fullDest)) {
                    copy($placeholder, $fullDest);
                }
                if (file_exists($fullDest)) {
                    $brand->logo = $dest;
                    $brand->save();
                }
            }
        }
    }
}
