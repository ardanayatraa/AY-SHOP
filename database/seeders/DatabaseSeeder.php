<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'admin@ayshop.id'],
            [
                'name' => 'Admin AY SHOP',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081100000000',
                'address' => 'Kantor Pusat AY SHOP, Jakarta',
                'email_verified_at' => now(),
            ]
        );

        // Customer account
        User::updateOrCreate(
            ['email' => 'budi@ayshop.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081298765432',
                'address' => 'Jl. Jend. Sudirman No. 10, Jakarta Pusat',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            CategorySeeder::class,
            SellerSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
