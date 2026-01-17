<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Vendor;
use Carbon\Carbon;

class VendorStorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Copies the sample story video into each vendor's stories folder
     * and inserts a DB record into `stories` if the table exists.
     */
    public function run()
    {
        $samplePath = public_path('uploads/stories/Big_Buck_Bunny_720_10s_2MB.mp4');
        if (!file_exists($samplePath)) {
            $this->command->warn("Sample story not found at {$samplePath}. Seeder will copy nothing.");
            return;
        }

        $vendors = Vendor::all();
        if ($vendors->isEmpty()) {
            $this->command->info('No vendors found.');
            return;
        }

        foreach ($vendors as $vendor) {
            $destDir = public_path("uploads/stories/{$vendor->id}");
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            $filename = Str::slug($vendor->name ?: "vendor-{$vendor->id}") . '-' . time() . '.mp4';
            $destPath = $destDir . DIRECTORY_SEPARATOR . $filename;

            copy($samplePath, $destPath);

            $relativePath = 'uploads/stories/' . $vendor->id . '/' . $filename;

            // If `vendor_stories` table exists, insert a record. Guarded to avoid errors.
            if (Schema::hasTable('vendor_stories')) {
                try {
                    \DB::table('vendor_stories')->insert([
                        'vendor_id' => $vendor->id,
                        'title' => 'Sample story video',
                        'media_type' => 'video',
                        'media_path' => $relativePath,
                        'thumb_path' => null,
                        'duration_seconds' => 10,
                        'sort_order' => 0,
                        'status' => 'active',
                        'start_at' => Carbon::now(),
                        'end_at' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    $this->command->warn("Failed to insert vendor_stories record for vendor {$vendor->id}: " . $e->getMessage());
                }
            } else {
                $this->command->warn('Table `vendor_stories` does not exist — skipping DB inserts (files were copied).');
            }

            $this->command->info("Added story for vendor {$vendor->id} -> {$relativePath}");
        }
    }
}
