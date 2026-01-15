<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample vendors for testing
        $vendors = [
            [
                'full_name' => 'Ahmed Electronics Store',
                'phone' => '+966500000001',
                'email' => 'ahmed@electronics.sa',
                'second_phone' => '+966500000002',
                'password' => Hash::make('password123'),
                'bio' => 'Best electronics store in Riyadh with 10 years of experience',
                'national_id' => '1234567890',
                'national_address' => 'King Fahd Road, Riyadh, Saudi Arabia',
                'lat' => 24.7136,
                'lng' => 46.6753,
                'status' => 'active',
            ],
            [
                'full_name' => 'Fatima Fashion Boutique',
                'phone' => '+966500000011',
                'email' => 'fatima@fashion.sa',
                'password' => Hash::make('password123'),
                'bio' => 'Premium fashion and accessories for women',
                'national_id' => '9876543210',
                'national_address' => 'Tahlia Street, Jeddah, Saudi Arabia',
                'lat' => 21.4858,
                'lng' => 39.1925,
                'status' => 'pending',
            ],
            [
                'full_name' => 'Mohammed Tech Shop',
                'phone' => '+966500000021',
                'email' => 'mohammed@techshop.sa',
                'password' => Hash::make('password123'),
                'bio' => 'Latest gadgets and technology products',
                'national_id' => '5555555555',
                'national_address' => 'Al Khobar, Eastern Province, Saudi Arabia',
                'lat' => 26.2172,
                'lng' => 50.1971,
                'status' => 'active',
            ],
        ];

        foreach ($vendors as $vendorData) {
            Vendor::updateOrCreate(
                ['phone' => $vendorData['phone']],
                $vendorData
            );
        }

        $this->command->info('Sample vendors created successfully!');
        $this->command->info('Test credentials: phone: +966500000001, password: password123');
    }
}
