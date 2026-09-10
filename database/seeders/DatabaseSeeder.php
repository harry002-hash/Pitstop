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
        // Akun pemilik bengkel (Ajung). Password:AJUNG ganti setelah login pertama.
        User::firstOrCreate(
            ['username' => 'ajung'],
            ['password' => 'bengkel123', 'role' => 'owner'],
        );
    }
}
