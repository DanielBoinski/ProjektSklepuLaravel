<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sprawdzamy, czy admin już istnieje
        if (!User::where('email', 'admin@sklep.test')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@sklep.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }
    }
}
