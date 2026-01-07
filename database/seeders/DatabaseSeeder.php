<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // keep a single test user (idempotent)
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'full_name' => 'Test User',
            'country_code' => '+966',
            'phone' => '500000000',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Seed users with realistic addresses
        $this->call([UsersTableSeeder::class]);

        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            \Database\Seeders\BrandSeeder::class,
            \Database\Seeders\BankSeeder::class,
            \Database\Seeders\VendorSeeder::class,
        ]);
    }
}
