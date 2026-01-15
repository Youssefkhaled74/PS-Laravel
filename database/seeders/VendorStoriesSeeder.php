<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorStory;
use App\Models\VendorBusinessProfile;
use Illuminate\Database\Seeder;

class VendorStoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 vendors if they don't exist
        $vendors = [];
        $countries = ['SA', 'AE', 'KW', 'BH', 'QA'];
        
        for ($i = 1; $i <= 5; $i++) {
            $vendor = Vendor::firstOrCreate(
                ['email' => "vendor{$i}@example.com"],
                [
                    'name' => "Vendor {$i}",
                    'phone' => "+96650000000{$i}",
                    'whatsapp_phone' => "+96650000000{$i}",
                    'bio' => "Test vendor {$i} for stories feature",
                    'avatar' => null,
                    'location_text' => 'Riyadh, Saudi Arabia',
                    'national_id' => "100000000{$i}",
                    'password' => bcrypt('password'),
                    'status' => 'active',
                ]
            );

            // Create business profile with country code
            VendorBusinessProfile::firstOrCreate(
                ['vendor_id' => $vendor->id],
                [
                    'company_name' => "Company {$i}",
                    'commercial_registration' => "CR100000{$i}",
                    'country_code' => $countries[$i - 1],
                    'city' => 'Riyadh',
                ]
            );

            $vendors[] = $vendor;
        }

        // Create stories for each vendor
        foreach ($vendors as $vendor) {
            $storiesCount = rand(2, 4);
            
            for ($j = 1; $j <= $storiesCount; $j++) {
                $mediaType = $j % 2 === 0 ? 'video' : 'image';
                $duration = $mediaType === 'image' ? rand(3, 7) : rand(10, 30);

                VendorStory::create([
                    'vendor_id' => $vendor->id,
                    'title' => "Story {$j} for {$vendor->name}",
                    'media_type' => $mediaType,
                    'media_path' => "uploads/stories/placeholder_{$mediaType}.jpg", // placeholder
                    'thumb_path' => null,
                    'duration_seconds' => $duration,
                    'sort_order' => $j,
                    'status' => 'active',
                    'start_at' => now()->subDays(rand(0, 5)),
                    'end_at' => now()->addDays(rand(5, 30)),
                ]);
            }
        }

        $this->command->info('✓ Created ' . count($vendors) . ' vendors with stories');
        $this->command->info('✓ Total stories created: ' . VendorStory::count());
    }
}
