<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::firstOrCreate([
            'email' => 'admin@ps.test'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password123'),
        ]);
    }
}
