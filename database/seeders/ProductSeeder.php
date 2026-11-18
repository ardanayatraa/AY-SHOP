<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $imageDir = public_path('images/products');
        File::ensureDirectoryExists($imageDir);

        $sellers = Seller::all()->keyBy('slug');
        $categories = Category::all()->keyBy('slug');

        $products = [
            // Nusantara Apparel (Bandung)
            [
                'seller' => 'nusantara-apparel',
                'category' => 'kaos-t-shirt',
                'name' => 'Kaos Oversized Heavyweight 24s',
                'price' => 149000,
                'discount_price' => 119000,
                'stock' => 45,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Hitam', 'Putih Tulang', 'Sage Green'],
                'material' => 'Cotton Combed 24s',
                'is_featured' => true,
                'sold_count' => 128,
                'shape' => 'tshirt',
                'palette' => ['#1e293b', '#334155', '#f8fafc'],
                'description' => 'Kaos oversized dengan potongan boxy modern menggunakan bahan Cotton Combed 24s yang tebal, adem, dan tidak menerawang. Cocok untuk gaya streetwear harian.',
            ],
            [
                'seller' => 'nusantara-apparel',
                'category' => 'kaos-t-shirt',
                'name' => 'Basic Crewneck Tee Nusantara',
                'price' => 99000,
                'discount_price' => null,
                'stock' => 60,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Navy', 'Maroon', 'Abu Misty'],
                'material' => 'Cotton Combed 30s',
                'is_featured' => false,
                'sold_count' => 94,
                'shape' => 'tshirt',
                'palette' => ['#1e3a8a', '#3b82f6', '#eff6ff'],
                'description' => 'Kaos basic sehari-hari dengan jahitan rantai rapi di bagian bahu dan kerah rib elastis yang tahan cuci.',
            ],
            [
                'seller' => 'nusantara-apparel',
                'category' => 'jaket-hoodie',
                'name' => 'Hoodie Boxy Fleece Heritage',
                'price' => 329000,
                'discount_price' => 279000,
                'stock' => 24,
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Charcoal', 'Cream', 'Forest Green'],
                'material' => 'Cotton Fleece 330gsm',
                'is_featured' => true,
                'sold_count' => 76,
                'shape' => 'hoodie',
                'palette' => ['#374151', '#4b5563', '#f3f4f6'],
                'description' => 'Hoodie boxy fit berbahan Cotton Fleece 330gsm yang lembut di bagian dalam dan kokoh di luar. Dilengkapi saku kanguru dan tali hoodie metal tip.',
            ],
            [
                'seller' => 'nusantara-apparel',
                'category' => 'celana-rok',
                'name' => 'Celana Cargo Relaxed Ripstop',
                'price' => 289000,
                'discount_price' => 249000,
                'stock' => 30,
                'sizes' => ['28', '30', '32', '34'],
                'colors' => ['Olive', 'Hitam', 'Khaki'],
                'material' => 'Katun Ripstop Premium',
                'is_featured' => false,
                'sold_count' => 52,
                'shape' => 'pants',
                'palette' => ['#3f6212', '#65a30d', '#f7fee7'],
                'description' => 'Celana cargo 6 saku berbahan ripstop ringan namun kuat. Pinggang dilengkapi adjuster dan tali serut di bagian ujung kaki.',
            ],
            [
                'seller' => 'nusantara-apparel',
                'category' => 'aksesoris-tas',
                'name' => 'Tote Bag Kanvas Tebal AY',
                'price' => 89000,
                'discount_price' => 69000,
                'stock' => 50,
                'sizes' => ['All Size'],
                'colors' => ['Broken White', 'Hitam'],
                'material' => 'Kanvas Marsoto 14oz',
                'is_featured' => false,
                'sold_count' => 110,
                'shape' => 'bag',
                'palette' => ['#78350f', '#d97706', '#fef3c7'],
                'description' => 'Tote bag kanvas tebal muat laptop hingga 15 inch, dilengkapi resleting utama dan saku kecil di bagian dalam.',
            ],

            // Batik & Linen Studio (Yogyakarta)
            [
                'seller' => 'batik-linen-studio',
                'category' => 'kemeja-blouse',
                'name' => 'Kemeja Linen Kerah Mandarin',
                'price' => 245000,
                'discount_price' => 199000,
                'stock' => 32,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Oatmeal', 'Putih', 'Sage'],
                'material' => '100% Cotton Linen',
                'is_featured' => true,
                'sold_count' => 88,
                'shape' => 'shirt',
                'palette' => ['#92400e', '#d97706', '#fffbeb'],
                'description' => 'Kemeja lengan panjang berbahan katun linen breathable dengan kerah mandarin yang rapi untuk acara formal maupun kasual.',
            ],
            [
                'seller' => 'batik-linen-studio',
                'category' => 'kemeja-blouse',
                'name' => 'Kemeja Batik Modern Parang Slim',
                'price' => 295000,
                'discount_price' => null,
                'stock' => 20,
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Sogan Coklat', 'Biru Indigo'],
                'material' => 'Katun Primisima Lapis Furing',
                'is_featured' => true,
                'sold_count' => 64,
                'shape' => 'shirt',
                'palette' => ['#7c2d12', '#c2410c', '#fff7ed'],
                'description' => 'Kemeja batik cap motif parang kontemporer dengan furing katun halus sehingga nyaman dan tidak panas.',
            ],
            [
                'seller' => 'batik-linen-studio',
                'category' => 'kemeja-blouse',
                'name' => 'Blouse Linen Ruffle Lengan Puff',
                'price' => 215000,
                'discount_price' => 179000,
                'stock' => 28,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Terracotta', 'Cream', 'Dusty Pink'],
                'material' => 'Linen Rami Premium',
                'is_featured' => false,
                'sold_count' => 41,
                'shape' => 'shirt',
                'palette' => ['#be185d', '#f472b6', '#fdf2f8'],
                'description' => 'Blouse wanita dengan detail lengan puff manis dan kancing depan aktif, mudah dipadukan dengan celana kulot atau rok.',
            ],
            [
                'seller' => 'batik-linen-studio',
                'category' => 'celana-rok',
                'name' => 'Celana Kulot Linen Highwaist',
                'price' => 229000,
                'discount_price' => null,
                'stock' => 35,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Beige', 'Hitam', 'Mocca'],
                'material' => 'Cotton Linen',
                'is_featured' => false,
                'sold_count' => 59,
                'shape' => 'pants',
                'palette' => ['#713f12', '#ca8a04', '#fefce8'],
                'description' => 'Kulot highwaist potongan lebar yang memberi efek jenjang, dilengkapi karet pinggang belakang dan saku kanan kiri.',
            ],
            [
                'seller' => 'batik-linen-studio',
                'category' => 'dress-gamis',
                'name' => 'Midi Dress Batik Lurik Kontemporer',
                'price' => 349000,
                'discount_price' => 299000,
                'stock' => 18,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Coklat Kopi', 'Abu Tenun'],
                'material' => 'Tenun Lurik & Katun',
                'is_featured' => false,
                'sold_count' => 33,
                'shape' => 'dress',
                'palette' => ['#57534e', '#a8a29e', '#fafaf9'],
                'description' => 'Perpaduan tenun lurik tradisional dan potongan midi dress A-line modern, cocok untuk acara semi-formal.',
            ],

            // Urban Outerwear ID (Jakarta Selatan)
            [
                'seller' => 'urban-outerwear-id',
                'category' => 'jaket-hoodie',
                'name' => 'Jaket Denim Washed Classic',
                'price' => 399000,
                'discount_price' => 339000,
                'stock' => 22,
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Light Blue', 'Dark Indigo', 'Black Wash'],
                'material' => 'Denim 13.5oz Non-Stretch',
                'is_featured' => true,
                'sold_count' => 83,
                'shape' => 'hoodie',
                'palette' => ['#1d4ed8', '#60a5fa', '#eff6ff'],
                'description' => 'Jaket trucker denim klasik dengan efek washed natural, kancing logam antikarat, dan 4 saku fungsional.',
            ],
            [
                'seller' => 'urban-outerwear-id',
                'category' => 'jaket-hoodie',
                'name' => 'Bomber Jacket Urban Minimalist',
                'price' => 359000,
                'discount_price' => null,
                'stock' => 25,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Army Green', 'Hitam', 'Navy'],
                'material' => 'Taslan Waterproof & Furing Satin',
                'is_featured' => false,
                'sold_count' => 47,
                'shape' => 'hoodie',
                'palette' => ['#065f46', '#10b981', '#ecfdf5'],
                'description' => 'Jaket bomber tahan angin dan gerimis ringan, dilengkapi resleting YKK dan saku lengan.',
            ],
            [
                'seller' => 'urban-outerwear-id',
                'category' => 'celana-rok',
                'name' => 'Celana Chino Slim Tapered',
                'price' => 259000,
                'discount_price' => 219000,
                'stock' => 40,
                'sizes' => ['28', '30', '32', '34', '36'],
                'colors' => ['Khaki', 'Navy', 'Hitam', 'Olive'],
                'material' => 'Cotton Twill Stretch',
                'is_featured' => true,
                'sold_count' => 115,
                'shape' => 'pants',
                'palette' => ['#9a3412', '#fb923c', '#fff7ed'],
                'description' => 'Celana chino berbahan cotton twill stretch yang lentur mengikuti gerak tubuh, rapi untuk ke kantor maupun nongkrong.',
            ],
            [
                'seller' => 'urban-outerwear-id',
                'category' => 'kemeja-blouse',
                'name' => 'Kemeja Flanel Kotak Heritage',
                'price' => 229000,
                'discount_price' => 189000,
                'stock' => 30,
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Merah Hitam', 'Coklat Navy'],
                'material' => 'Wool-Touch Cotton Flannel',
                'is_featured' => false,
                'sold_count' => 62,
                'shape' => 'shirt',
                'palette' => ['#991b1b', '#ef4444', '#fef2f2'],
                'description' => 'Kemeja flanel lembut yang nyaman dipakai langsung atau dijadikan outer di atas kaos putih.',
            ],
            [
                'seller' => 'urban-outerwear-id',
                'category' => 'aksesoris-tas',
                'name' => 'Topi Baseball Corduroy Urban',
                'price' => 95000,
                'discount_price' => null,
                'stock' => 48,
                'sizes' => ['All Size'],
                'colors' => ['Espresso', 'Forest', 'Hitam'],
                'material' => 'Corduroy Halus',
                'is_featured' => false,
                'sold_count' => 71,
                'shape' => 'bag',
                'palette' => ['#312e81', '#6366f1', '#eef2ff'],
                'description' => 'Topi 6-panel berbahan corduroy premium dengan strap pengatur besi di bagian belakang.',
            ],

            // Aura Modest & Dress (Surabaya)
            [
                'seller' => 'aura-modest-dress',
                'category' => 'dress-gamis',
                'name' => 'Gamis Silk Satin Ayla Series',
                'price' => 379000,
                'discount_price' => 319000,
                'stock' => 26,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Sage', 'Champagne', 'Mauve'],
                'material' => 'Armani Silk Premium',
                'is_featured' => true,
                'sold_count' => 104,
                'shape' => 'dress',
                'palette' => ['#0f766e', '#2dd4bf', '#f0fdfa'],
                'description' => 'Gamis elegan berbahan Armani Silk yang jatuh lembut, tidak mudah kusut, busui friendly dan wudhu friendly.',
            ],
            [
                'seller' => 'aura-modest-dress',
                'category' => 'dress-gamis',
                'name' => 'Midi Dress Floral Pastel Breeze',
                'price' => 269000,
                'discount_price' => 229000,
                'stock' => 29,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Lilac', 'Buttercream', 'Sky Blue'],
                'material' => 'Rayon Viscose Adem',
                'is_featured' => true,
                'sold_count' => 67,
                'shape' => 'dress',
                'palette' => ['#6d28d9', '#a78bfa', '#f5f3ff'],
                'description' => 'Midi dress motif bunga lembut dengan bahan rayon viscose yang sangat sejuk dan ringan.',
            ],
            [
                'seller' => 'aura-modest-dress',
                'category' => 'kemeja-blouse',
                'name' => 'Tunik Minimalis Kancing Sembunyi',
                'price' => 219000,
                'discount_price' => null,
                'stock' => 34,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Ivory', 'Latte', 'Hitam'],
                'material' => 'Toyobo Fodu Premium',
                'is_featured' => false,
                'sold_count' => 45,
                'shape' => 'shirt',
                'palette' => ['#a16207', '#eab308', '#fefce8'],
                'description' => 'Tunik potongan bersih berbahan katun Toyobo Fodu dengan saku samping dan kancing tersembunyi.',
            ],
            [
                'seller' => 'aura-modest-dress',
                'category' => 'celana-rok',
                'name' => 'Rok Plisket Maxi Premium',
                'price' => 159000,
                'discount_price' => 129000,
                'stock' => 55,
                'sizes' => ['All Size'],
                'colors' => ['Milo', 'Hitam', 'Broken White', 'Dusty Rose'],
                'material' => 'Hyget Super Pleated',
                'is_featured' => false,
                'sold_count' => 142,
                'shape' => 'pants',
                'palette' => ['#9d174d', '#ec4899', '#fdf2f8'],
                'description' => 'Rok plisket lipatan tajam dan rapi, pinggang full karet elastis nyaman dipakai dari ukuran S hingga XL.',
            ],
            [
                'seller' => 'aura-modest-dress',
                'category' => 'aksesoris-tas',
                'name' => 'Sling Bag Kulit Sintetis Aura',
                'price' => 189000,
                'discount_price' => 149000,
                'stock' => 38,
                'sizes' => ['All Size'],
                'colors' => ['Caramel', 'Hitam', 'Ivory'],
                'material' => 'PU Leather Halus',
                'is_featured' => false,
                'sold_count' => 79,
                'shape' => 'bag',
                'palette' => ['#b45309', '#f59e0b', '#fffbeb'],
                'description' => 'Tas selempang minimalis dengan kompartemen rapi dan tali yang dapat disesuaikan panjangnya.',
            ],
        ];

        foreach ($products as $item) {
            $slug = Str::slug($item['name']);
            $imagePath = 'images/products/' . $slug . '.svg';
            $this->generateProductSvg(
                public_path($imagePath),
                $item['shape'],
                $item['palette'],
                $item['name']
            );

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'seller_id' => $sellers[$item['seller']]->id,
                    'category_id' => $categories[$item['category']]->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'discount_price' => $item['discount_price'],
                    'stock' => $item['stock'],
                    'sizes' => $item['sizes'],
                    'colors' => $item['colors'],
                    'material' => $item['material'],
                    'image' => $imagePath,
                    'is_featured' => $item['is_featured'],
                    'is_active' => true,
                    'sold_count' => $item['sold_count'],
                ]
            );
        }
    }

    private function generateProductSvg(string $fullPath, string $shape, array $palette, string $title): void
    {
        [$primary, $accent, $bg] = $palette;
        $safeTitle = htmlspecialchars(Str::limit($title, 28), ENT_QUOTES, 'UTF-8');

        $garmentPath = match ($shape) {
            'tshirt' => '<path d="M165 110 L215 95 Q250 120 285 95 L335 110 L380 175 L340 205 L315 170 L315 360 L185 360 L185 170 L160 205 L120 175 Z" fill="' . $primary . '" stroke="' . $accent . '" stroke-width="4" stroke-linejoin="round"/><path d="M215 95 Q250 135 285 95" fill="none" stroke="' . $bg . '" stroke-width="5"/>',
            'shirt' => '<path d="M175 105 L220 92 L250 125 L280 92 L325 105 L375 180 L345 205 L315 165 L315 365 L185 365 L185 165 L155 205 L125 180 Z" fill="' . $primary . '" stroke="' . $accent . '" stroke-width="4" stroke-linejoin="round"/><line x1="250" y1="125" x2="250" y2="365" stroke="' . $bg . '" stroke-width="3" stroke-dasharray="6 14"/>',
            'hoodie' => '<path d="M195 95 Q250 65 305 95 L345 120 L385 215 L345 235 L320 175 L320 365 L180 365 L180 175 L155 235 L115 215 L155 120 Z" fill="' . $primary . '" stroke="' . $accent . '" stroke-width="4" stroke-linejoin="round"/><path d="M205 255 L295 255 L305 320 L195 320 Z" fill="' . $accent . '" opacity="0.5"/>',
            'pants' => '<path d="M175 100 L325 100 L340 370 L275 370 L250 215 L225 370 L160 370 Z" fill="' . $primary . '" stroke="' . $accent . '" stroke-width="4" stroke-linejoin="round"/><line x1="175" y1="125" x2="325" y2="125" stroke="' . $bg . '" stroke-width="4"/>',
            'dress' => '<path d="M210 95 L235 110 Q250 125 265 110 L290 95 L315 160 L295 190 L355 375 L145 375 L205 190 L185 160 Z" fill="' . $primary . '" stroke="' . $accent . '" stroke-width="4" stroke-linejoin="round"/><line x1="205" y1="190" x2="295" y2="190" stroke="' . $bg . '" stroke-width="4"/>',
            default => '<rect x="160" y="160" width="180" height="190" rx="22" fill="' . $primary . '" stroke="' . $accent . '" stroke-width="4"/><path d="M205 160 C205 100 295 100 295 160" fill="none" stroke="' . $primary . '" stroke-width="14" stroke-linecap="round"/><line x1="160" y1="215" x2="340" y2="215" stroke="' . $bg . '" stroke-width="3" opacity="0.6"/>',
        };

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="500" height="500">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$bg}" />
      <stop offset="100%" stop-color="#ffffff" />
    </linearGradient>
  </defs>
  <rect width="500" height="500" fill="url(#bgGrad)" />
  <circle cx="250" cy="230" r="165" fill="{$accent}" opacity="0.12" />
  {$garmentPath}
  <text x="250" y="435" font-family="system-ui, -apple-system, sans-serif" font-size="16" font-weight="600" fill="#475569" text-anchor="middle">{$safeTitle}</text>
  <text x="250" y="460" font-family="system-ui, -apple-system, sans-serif" font-size="11" letter-spacing="3" fill="#94a3b8" text-anchor="middle">AY SHOP COLLECTION</text>
</svg>
SVG;

        File::put($fullPath, $svg);
    }
}

