<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few specific real-like users
        User::factory()->create([
            'full_name' => 'Ahmed Al-Saud',
            'email' => 'ahmed@example.test',
            'phone' => '512345678',
        ])->each(function ($u) {
            // add one default address
            Address::factory()->create([
                'user_id' => $u->id,
                'label' => 'Home',
                'is_default' => true,
            ]);
        });

        User::factory()->create([
            'full_name' => 'Fatimah Al-Harbi',
            'email' => 'fatimah@example.test',
            'phone' => '512345679',
        ])->each(function ($u) {
            Address::factory()->create([
                'user_id' => $u->id,
                'label' => 'Home',
                'is_default' => true,
            ]);
        });

        // Bulk generate realistic users with 1-3 addresses each
        User::factory()->count(25)->create()->each(function (User $user) {
            $addrCount = rand(1, 3);
            $madeDefault = false;
            for ($i = 0; $i < $addrCount; $i++) {
                $addr = Address::factory()->create([
                    'user_id' => $user->id,
                    'is_default' => false,
                ]);
                if (! $madeDefault) {
                    $addr->is_default = true;
                    $addr->save();
                    $madeDefault = true;
                }
            }
        });
    }
}
