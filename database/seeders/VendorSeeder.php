<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class VendorSeeder extends Seeder
{
    public function run()
    {
        if (! Schema::hasTable('vendors')) {
            $this->command->warn('vendors table does not exist, skipping VendorSeeder.');
            return;
        }

        $faker = FakerFactory::create();

        $packagesAvailable = Schema::hasTable('vendor_packages') ? \App\Models\VendorPackage::where('status', 'active')->pluck('id')->all() : [];
        $banksAvailable = Schema::hasTable('banks') ? \App\Models\Bank::pluck('id')->all() : [];

        DB::transaction(function () use ($faker, $packagesAvailable, $banksAvailable) {
            for ($i = 1; $i <= 20; $i++) {
                $email = sprintf('vendor%02d@ps.test', $i);
                $phone = '+9665' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                $password = Hash::make('password123');

                $vendor = \App\Models\Vendor::updateOrCreate([
                    'email' => $email,
                ], [
                    'name' => $faker->company . ' ' . $i,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $password,
                    'status' => 'active',
                ]);

                \App\Models\VendorBusinessProfile::updateOrCreate([
                    'vendor_id' => $vendor->id,
                ], [
                    'commercial_name' => $vendor->name,
                    'id_number' => strtoupper($faker->bothify('ID########')),
                    'commercial_register_number' => strtoupper($faker->bothify('CR-######')),
                    'bank_id' => $banksAvailable ? $banksAvailable[array_rand($banksAvailable)] : null,
                    'bank_account_number' => strtoupper($faker->bothify('SA###############')),
                    'status' => 'pending',
                ]);

                // create a sample document
                if (Schema::hasTable('vendor_documents')) {
                    \App\Models\VendorDocument::updateOrCreate([
                        'vendor_id' => $vendor->id,
                        'type' => 'trade_license',
                    ], [
                        'file_path' => 'uploads/vendors/' . $vendor->id . '/trade_license.pdf',
                    ]);
                }

                // assign a package if available
                if ($packagesAvailable && Schema::hasTable('vendor_package_assignments')) {
                    $pkgId = $packagesAvailable[array_rand($packagesAvailable)];
                    $package = \App\Models\VendorPackage::find($pkgId);
                    if ($package) {
                        \App\Models\VendorPackageAssignment::updateOrCreate([
                            'vendor_id' => $vendor->id,
                        ], [
                            'vendor_package_id' => $pkgId,
                            'price' => $package->price ?? 0,
                            'currency' => $package->currency ?? 'SAR',
                            'starts_at' => now()->subDays(rand(0, 30)),
                            'ends_at' => now()->addMonths($package->duration_months ?? 1),
                            'status' => 'active',
                        ]);
                    }
                }
            }
        });

        $this->command->info('VendorSeeder completed.');
    }
}
