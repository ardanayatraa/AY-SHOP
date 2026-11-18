<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $sellersData = [
            [
                'user' => [
                    'name' => 'Andi Pratama',
                    'email' => 'seller1@ayshop.id',
                    'phone' => '081234567801',
                    'address' => 'Jl. Dago No. 45, Bandung',
                ],
                'store' => [
                    'store_name' => 'Nusantara Apparel',
                    'slug' => 'nusantara-apparel',
                    'description' => 'Brand streetwear lokal asal Bandung yang menghadirkan kaos katun combed 24s & 30s, hoodie boxy fit, dan celana cargo dengan kualitas jahitan distro terbaik.',
                    'city' => 'Bandung',
                    'phone' => '081234567801',
                    'rating' => 4.9,
                    'is_verified' => true,
                    'is_active' => true,
                ],
            ],
            [
                'user' => [
                    'name' => 'Sari Kusuma',
                    'email' => 'seller2@ayshop.id',
                    'phone' => '081234567802',
                    'address' => 'Jl. Malioboro No. 88, Yogyakarta',
                ],
                'store' => [
                    'store_name' => 'Batik & Linen Studio',
                    'slug' => 'batik-linen-studio',
                    'description' => 'Koleksi kemeja linen, batik kontemporer, dan busana kerja bernuansa natural yang nyaman dipakai seharian di iklim tropis.',
                    'city' => 'Yogyakarta',
                    'phone' => '081234567802',
                    'rating' => 4.8,
                    'is_verified' => true,
                    'is_active' => true,
                ],
            ],
            [
                'user' => [
                    'name' => 'Reza Mahendra',
                    'email' => 'seller3@ayshop.id',
                    'phone' => '081234567803',
                    'address' => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                ],
                'store' => [
                    'store_name' => 'Urban Outerwear ID',
                    'slug' => 'urban-outerwear-id',
                    'description' => 'Spesialis outerwear modern: jaket denim, coach jacket, bomber, dan chino pants dengan potongan tailored fit untuk gaya urban.',
                    'city' => 'Jakarta Selatan',
                    'phone' => '081234567803',
                    'rating' => 4.9,
                    'is_verified' => true,
                    'is_active' => true,
                ],
            ],
            [
                'user' => [
                    'name' => 'Nadia Putri',
                    'email' => 'seller4@ayshop.id',
                    'phone' => '081234567804',
                    'address' => 'Jl. Tunjungan No. 33, Surabaya',
                ],
                'store' => [
                    'store_name' => 'Aura Modest & Dress',
                    'slug' => 'aura-modest-dress',
                    'description' => 'Busana wanita modern, midi dress, blouse satin, gamis syari minimalis, serta aksesoris tas dan scarf dengan palet warna pastel & earth tone.',
                    'city' => 'Surabaya',
                    'phone' => '081234567804',
                    'rating' => 4.7,
                    'is_verified' => true,
                    'is_active' => true,
                ],
            ],
        ];

        foreach ($sellersData as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['user']['email']],
                [
                    'name' => $item['user']['name'],
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    'phone' => $item['user']['phone'],
                    'address' => $item['user']['address'],
                    'email_verified_at' => now(),
                ]
            );

            Seller::updateOrCreate(
                ['user_id' => $user->id],
                $item['store']
            );
        }
    }
}

