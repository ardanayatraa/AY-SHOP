<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kaos & T-Shirt',
                'slug' => 'kaos-t-shirt',
                'icon' => null,
                'description' => 'Kaos santai berbahan katun combed premium, oversized tee, hingga graphic tee.',
            ],
            [
                'name' => 'Kemeja & Blouse',
                'slug' => 'kemeja-blouse',
                'icon' => null,
                'description' => 'Kemeja linen, flanel, batik modern, dan blouse elegan untuk kerja maupun santai.',
            ],
            [
                'name' => 'Jaket & Hoodie',
                'slug' => 'jaket-hoodie',
                'icon' => null,
                'description' => 'Hoodie fleece tebal, jaket denim, bomber, dan cardigan hangat.',
            ],
            [
                'name' => 'Celana & Rok',
                'slug' => 'celana-rok',
                'icon' => null,
                'description' => 'Chinos, celana linen, cargo pants, denim, dan rok midi kekinian.',
            ],
            [
                'name' => 'Dress & Gamis',
                'slug' => 'dress-gamis',
                'icon' => null,
                'description' => 'Koleksi dress kasual, midi dress, dan gamis modern berbahan adem.',
            ],
            [
                'name' => 'Aksesoris & Tas',
                'slug' => 'aksesoris-tas',
                'icon' => null,
                'description' => 'Tote bag kanvas, topi baseball, scarf, dan ikat pinggang kulit.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}

