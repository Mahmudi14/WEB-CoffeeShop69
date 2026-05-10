<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdditionalMenuSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@69coffeeshop.test')->first();

        if (! $admin) {
            throw new \RuntimeException('Admin belum ada. Jalankan RolePermissionSeeder terlebih dahulu.');
        }

        $menusByCategory = [
            'coffee' => [
                ['name' => 'Latte', 'slug' => 'latte', 'description' => 'Espresso dengan susu steamed yang creamy.', 'normal_price' => 20000, 'sort_order' => 4],
                ['name' => 'Espresso', 'slug' => 'espresso', 'description' => 'Kopi pekat dengan rasa yang kuat.', 'normal_price' => 13000, 'sort_order' => 5],
                ['name' => 'Mochaccino', 'slug' => 'mochaccino', 'description' => 'Perpaduan espresso, susu, dan coklat.', 'normal_price' => 22000, 'sort_order' => 6],
                ['name' => 'Caramel Macchiato', 'slug' => 'caramel-macchiato', 'description' => 'Espresso susu dengan sentuhan caramel.', 'normal_price' => 24000, 'sort_order' => 7],
                ['name' => 'Vanilla Latte', 'slug' => 'vanilla-latte', 'description' => 'Latte dengan aroma vanilla yang lembut.', 'normal_price' => 23000, 'sort_order' => 8],
                ['name' => 'Hazelnut Latte', 'slug' => 'hazelnut-latte', 'description' => 'Latte dengan rasa hazelnut yang manis gurih.', 'normal_price' => 23000, 'sort_order' => 9],
                ['name' => 'V60', 'slug' => 'v60', 'description' => 'Manual brew dengan metode V60.', 'normal_price' => 22000, 'sort_order' => 10],
                ['name' => 'Japanese Iced Coffee', 'slug' => 'japanese-iced-coffee', 'description' => 'Manual brew dingin dengan karakter kopi yang segar.', 'normal_price' => 24000, 'sort_order' => 11],
                ['name' => 'Affogato', 'slug' => 'affogato', 'description' => 'Espresso yang disajikan dengan es krim vanilla.', 'normal_price' => 25000, 'sort_order' => 12],
            ],

            'non-coffee' => [
                ['name' => 'Matcha Latte', 'slug' => 'matcha-latte', 'description' => 'Matcha dengan susu yang creamy.', 'normal_price' => 22000, 'sort_order' => 3],
                ['name' => 'Red Velvet', 'slug' => 'red-velvet', 'description' => 'Minuman red velvet manis dan lembut.', 'normal_price' => 21000, 'sort_order' => 4],
                ['name' => 'Taro Latte', 'slug' => 'taro-latte', 'description' => 'Minuman taro dengan susu segar.', 'normal_price' => 21000, 'sort_order' => 5],
                ['name' => 'Lemon Tea', 'slug' => 'lemon-tea', 'description' => 'Teh segar dengan rasa lemon.', 'normal_price' => 12000, 'sort_order' => 6],
                ['name' => 'Lychee Tea', 'slug' => 'lychee-tea', 'description' => 'Teh manis dengan aroma leci.', 'normal_price' => 15000, 'sort_order' => 7],
                ['name' => 'Milk Tea', 'slug' => 'milk-tea', 'description' => 'Teh susu dengan rasa ringan dan creamy.', 'normal_price' => 16000, 'sort_order' => 8],
                ['name' => 'Mineral Water', 'slug' => 'mineral-water', 'description' => 'Air mineral botol.', 'normal_price' => 6000, 'sort_order' => 9],
                ['name' => 'Strawberry Yakult', 'slug' => 'strawberry-yakult', 'description' => 'Minuman segar strawberry dengan yakult.', 'normal_price' => 18000, 'sort_order' => 10],
                ['name' => 'Mango Yakult', 'slug' => 'mango-yakult', 'description' => 'Minuman segar mangga dengan yakult.', 'normal_price' => 18000, 'sort_order' => 11],
                ['name' => 'Cookies and Cream', 'slug' => 'cookies-and-cream', 'description' => 'Minuman susu dengan rasa cookies yang creamy.', 'normal_price' => 23000, 'sort_order' => 12],
            ],

            'food' => [
                ['name' => 'Chicken Rice Bowl', 'slug' => 'chicken-rice-bowl', 'description' => 'Nasi dengan topping ayam berbumbu.', 'normal_price' => 28000, 'sort_order' => 3],
                ['name' => 'Beef Rice Bowl', 'slug' => 'beef-rice-bowl', 'description' => 'Nasi dengan topping beef gurih.', 'normal_price' => 32000, 'sort_order' => 4],
                ['name' => 'Ayam Geprek', 'slug' => 'ayam-geprek', 'description' => 'Ayam crispy dengan sambal geprek.', 'normal_price' => 25000, 'sort_order' => 5],
                ['name' => 'Nasi Ayam Teriyaki', 'slug' => 'nasi-ayam-teriyaki', 'description' => 'Nasi dengan ayam saus teriyaki.', 'normal_price' => 28000, 'sort_order' => 6],
                ['name' => 'Spaghetti Bolognese', 'slug' => 'spaghetti-bolognese', 'description' => 'Pasta dengan saus bolognese.', 'normal_price' => 30000, 'sort_order' => 7],
                ['name' => 'Spaghetti Carbonara', 'slug' => 'spaghetti-carbonara', 'description' => 'Pasta creamy dengan saus carbonara.', 'normal_price' => 32000, 'sort_order' => 8],
                ['name' => 'Chicken Katsu', 'slug' => 'chicken-katsu', 'description' => 'Ayam katsu renyah dengan nasi.', 'normal_price' => 29000, 'sort_order' => 9],
                ['name' => 'Nasi Uduk Ayam', 'slug' => 'nasi-uduk-ayam', 'description' => 'Nasi uduk dengan ayam dan pelengkap.', 'normal_price' => 26000, 'sort_order' => 10],
                ['name' => 'Mie Kuah', 'slug' => 'mie-kuah', 'description' => 'Mie kuah hangat dengan topping sederhana.', 'normal_price' => 22000, 'sort_order' => 11],
                ['name' => 'Sate Taichan', 'slug' => 'sate-taichan', 'description' => 'Sate ayam taichan dengan sambal pedas.', 'normal_price' => 27000, 'sort_order' => 12],
            ],

            'snack' => [
                ['name' => 'Pisang Goreng', 'slug' => 'pisang-goreng', 'description' => 'Pisang goreng hangat dan renyah.', 'normal_price' => 14000, 'sort_order' => 3],
                ['name' => 'Cireng', 'slug' => 'cireng', 'description' => 'Cireng crispy dengan saus cocol.', 'normal_price' => 13000, 'sort_order' => 4],
                ['name' => 'Tahu Crispy', 'slug' => 'tahu-crispy', 'description' => 'Tahu goreng crispy dengan bumbu gurih.', 'normal_price' => 14000, 'sort_order' => 5],
                ['name' => 'Onion Ring', 'slug' => 'onion-ring', 'description' => 'Bawang bombay goreng tepung.', 'normal_price' => 16000, 'sort_order' => 6],
                ['name' => 'Chicken Nugget', 'slug' => 'chicken-nugget', 'description' => 'Nugget ayam dengan saus pilihan.', 'normal_price' => 17000, 'sort_order' => 7],
                ['name' => 'Sosis Bakar', 'slug' => 'sosis-bakar', 'description' => 'Sosis bakar dengan saus manis pedas.', 'normal_price' => 18000, 'sort_order' => 8],
                ['name' => 'Donat Kentang', 'slug' => 'donat-kentang', 'description' => 'Donat kentang lembut dengan topping manis.', 'normal_price' => 12000, 'sort_order' => 9],
                ['name' => 'Pancake Mini', 'slug' => 'pancake-mini', 'description' => 'Pancake mini dengan topping manis.', 'normal_price' => 18000, 'sort_order' => 10],
                ['name' => 'Nachos', 'slug' => 'nachos', 'description' => 'Tortilla chips dengan saus gurih.', 'normal_price' => 22000, 'sort_order' => 11],
                ['name' => 'Singkong Keju', 'slug' => 'singkong-keju', 'description' => 'Singkong goreng dengan taburan keju.', 'normal_price' => 16000, 'sort_order' => 12],
            ],
        ];

        foreach ($menusByCategory as $categorySlug => $menus) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                throw new \RuntimeException("Kategori {$categorySlug} belum ada. Jalankan MasterDataSeeder terlebih dahulu.");
            }

            foreach ($menus as $menu) {
                Menu::updateOrCreate(
                    ['slug' => $menu['slug']],
                    [
                        ...$menu,
                        'category_id' => $category->id,
                        'image_path' => null,
                        'is_active' => true,
                        'is_available' => true,
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]
                );
            }
        }
    }
}
