<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@jst.com')->exists()) {
            User::create([
                'first_name' => 'Admin',
                'last_name' => 'JST',
                'email' => 'admin@jst.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]);
        }
    }
}
