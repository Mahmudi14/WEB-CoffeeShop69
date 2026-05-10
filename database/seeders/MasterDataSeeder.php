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

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::where('email', 'superadmin@69coffeeshop.test')->first();

        if (! $superadmin) {
            throw new \RuntimeException('Superadmin belum ada. Jalankan RolePermissionSeeder terlebih dahulu.');
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
        $coffee = Category::where('slug', 'coffee')->firstOrFail();
        $nonCoffee = Category::where('slug', 'non-coffee')->firstOrFail();
        $food = Category::where('slug', 'food')->firstOrFail();
        $snack = Category::where('slug', 'snack')->firstOrFail();

        $menus = [
            [
                'category_id' => $coffee->id,
                'name' => 'Kopi Susu',
                'slug' => 'kopi-susu',
                'description' => 'Kopi susu gula aren.',
                'normal_price' => 18000,
                'sort_order' => 1,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Americano',
                'slug' => 'americano',
                'description' => 'Espresso dengan air panas.',
                'normal_price' => 15000,
                'sort_order' => 2,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Cappuccino',
                'slug' => 'cappuccino',
                'description' => 'Espresso, susu, dan foam.',
                'normal_price' => 20000,
                'sort_order' => 3,
            ],
            [
                'category_id' => $nonCoffee->id,
                'name' => 'Es Teh',
                'slug' => 'es-teh',
                'description' => 'Es teh manis.',
                'normal_price' => 7000,
                'sort_order' => 1,
            ],
            [
                'category_id' => $nonCoffee->id,
                'name' => 'Chocolate',
                'slug' => 'chocolate',
                'description' => 'Minuman coklat.',
                'normal_price' => 18000,
                'sort_order' => 2,
            ],
            [
                'category_id' => $food->id,
                'name' => 'Nasi Goreng',
                'slug' => 'nasi-goreng',
                'description' => 'Nasi goreng spesial.',
                'normal_price' => 25000,
                'sort_order' => 1,
            ],
            [
                'category_id' => $food->id,
                'name' => 'Mie Goreng',
                'slug' => 'mie-goreng',
                'description' => 'Mie goreng spesial.',
                'normal_price' => 23000,
                'sort_order' => 2,
            ],
            [
                'category_id' => $snack->id,
                'name' => 'Kentang Goreng',
                'slug' => 'kentang-goreng',
                'description' => 'French fries.',
                'normal_price' => 15000,
                'sort_order' => 1,
            ],
            [
                'category_id' => $snack->id,
                'name' => 'Roti Bakar',
                'slug' => 'roti-bakar',
                'description' => 'Roti bakar manis.',
                'normal_price' => 16000,
                'sort_order' => 2,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['slug' => $menu['slug']],
                [
                    ...$menu,
                    'image_path' => null,
                    'is_active' => true,
                    'is_available' => true,
                    'created_by' => $superadmin->id,
                    'updated_by' => $superadmin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cafe Tables
        |--------------------------------------------------------------------------
        */
        for ($i = 1; $i <= 10; $i++) {
            CafeTable::updateOrCreate(
                ['code' => 'TBL-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => 'Meja ' . $i,
                    'qr_token' => 'table-' . $i . '-' . Str::random(32),
                    'is_active' => true,
                    'created_by' => $superadmin->id,
                    'updated_by' => $superadmin->id,
                ]
            );
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
        $allMenuPromo = Promotion::updateOrCreate(
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
}