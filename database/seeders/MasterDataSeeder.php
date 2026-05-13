<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Promotion;
use App\Models\TaxSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::where('email', 'superadmin@69coffeeshop.test')->first();

        if (! $superadmin) {
            throw new RuntimeException('Superadmin belum ada. Jalankan RolePermissionSeeder terlebih dahulu.');
        }

        $admin = User::where('email', 'mahmudi@gmail.com')->first();

        if (! $admin) {
            throw new RuntimeException('Admin belum ada. Jalankan RolePermissionSeeder terlebih dahulu.');
        }

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        $categories = [
            [
                'name' => 'Coffee',
                'slug' => 'coffee',
                'description' => 'Menu minuman berbasis kopi.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Non Coffee',
                'slug' => 'non-coffee',
                'description' => 'Menu minuman tanpa kopi.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Menu makanan.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Snack',
                'slug' => 'snack',
                'description' => 'Menu camilan.',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    ...$category,
                    'is_active' => true,
                    'created_by' => $superadmin->id,
                    'updated_by' => $superadmin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Menus
        |--------------------------------------------------------------------------
        */
        $baseMenusByCategory = [
            'coffee' => [
                ['name' => 'Kopi Susu', 'slug' => 'kopi-susu', 'description' => 'Kopi susu gula aren.', 'normal_price' => 18000, 'sort_order' => 1],
                ['name' => 'Americano', 'slug' => 'americano', 'description' => 'Espresso dengan air panas.', 'normal_price' => 15000, 'sort_order' => 2],
                ['name' => 'Cappuccino', 'slug' => 'cappuccino', 'description' => 'Espresso, susu, dan foam.', 'normal_price' => 20000, 'sort_order' => 3],
            ],
            'non-coffee' => [
                ['name' => 'Es Teh', 'slug' => 'es-teh', 'description' => 'Es teh manis.', 'normal_price' => 7000, 'sort_order' => 1],
                ['name' => 'Chocolate', 'slug' => 'chocolate', 'description' => 'Minuman coklat.', 'normal_price' => 18000, 'sort_order' => 2],
            ],
            'food' => [
                ['name' => 'Nasi Goreng', 'slug' => 'nasi-goreng', 'description' => 'Nasi goreng spesial.', 'normal_price' => 25000, 'sort_order' => 1],
                ['name' => 'Mie Goreng', 'slug' => 'mie-goreng', 'description' => 'Mie goreng spesial.', 'normal_price' => 23000, 'sort_order' => 2],
            ],
            'snack' => [
                ['name' => 'Kentang Goreng', 'slug' => 'kentang-goreng', 'description' => 'French fries.', 'normal_price' => 15000, 'sort_order' => 1],
                ['name' => 'Roti Bakar', 'slug' => 'roti-bakar', 'description' => 'Roti bakar manis.', 'normal_price' => 16000, 'sort_order' => 2],
            ],
        ];

        $additionalMenusByCategory = [
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

        $this->seedMenusByCategory($baseMenusByCategory, $superadmin);
        $this->seedMenusByCategory($additionalMenusByCategory, $admin);

        /*
        |--------------------------------------------------------------------------
        | Cafe Tables
        |--------------------------------------------------------------------------
        */
        for ($i = 1; $i <= 10; $i++) {
            $table = CafeTable::firstOrNew([
                'code' => 'TBL-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            ]);

            $table->name = 'Meja ' . $i;
            $table->is_active = true;
            $table->created_by = $table->created_by ?: $superadmin->id;
            $table->updated_by = $superadmin->id;

            if (! $table->exists || blank($table->qr_token)) {
                $table->qr_token = 'table-' . $i . '-' . Str::random(32);
            }

            $table->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Tax Setting
        |--------------------------------------------------------------------------
        */
        TaxSetting::updateOrCreate(
            ['name' => 'PPN'],
            [
                'rate' => 11.00,
                'is_active' => true,
                'price_includes_tax' => false,
                'created_by' => $superadmin->id,
                'updated_by' => $superadmin->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Promotions
        |--------------------------------------------------------------------------
        | Promo dibuat nonaktif dulu agar tidak mengganggu testing awal.
        |--------------------------------------------------------------------------
        */
        Promotion::updateOrCreate(
            ['name' => 'Promo Semua Menu 10%'],
            [
                'description' => 'Contoh promo untuk semua menu.',
                'scope' => 'all_menu',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'priority' => 1,
                'starts_at' => now()->startOfDay(),
                'ends_at' => now()->addDays(7)->endOfDay(),
                'is_active' => false,
                'created_by' => $superadmin->id,
                'updated_by' => $superadmin->id,
            ]
        );

        $selectedMenuPromo = Promotion::updateOrCreate(
            ['name' => 'Promo Kopi Susu Rp3.000'],
            [
                'description' => 'Contoh promo khusus menu Kopi Susu.',
                'scope' => 'selected_menu',
                'discount_type' => 'fixed',
                'discount_value' => 3000.00,
                'priority' => 2,
                'starts_at' => now()->startOfDay(),
                'ends_at' => now()->addDays(7)->endOfDay(),
                'is_active' => false,
                'created_by' => $superadmin->id,
                'updated_by' => $superadmin->id,
            ]
        );

        $kopiSusu = Menu::where('slug', 'kopi-susu')->first();

        if ($kopiSusu) {
            $selectedMenuPromo->menus()->syncWithoutDetaching([
                $kopiSusu->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | App Settings
        |--------------------------------------------------------------------------
        */
        $settings = [
            'cafe_name' => '69 Coffee Shop',
            'cafe_address' => 'Alamat cafe belum diatur',
            'cafe_phone' => '08xxxxxxxxxx',
            'receipt_footer' => 'Terima kasih sudah berkunjung.',
            'currency' => 'IDR',
        ];

        foreach ($settings as $key => $value) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => 'string',
                ]
            );
        }
    }

    private function seedMenusByCategory(array $menusByCategory, User $creator): void
    {
        foreach ($menusByCategory as $categorySlug => $menus) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                throw new RuntimeException("Kategori {$categorySlug} belum ada.");
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
                        'created_by' => $creator->id,
                        'updated_by' => $creator->id,
                    ]
                );
            }
        }
    }
}