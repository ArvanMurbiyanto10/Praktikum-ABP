<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat User untuk Login
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Praktikum',
                'password' => Hash::make('password123'),
            ]
        );

        // Buat Dummy Data Produk
        $product1 = Product::create([
            'name' => 'Honda HRV 2022',
            'price' => 36000000
        ]);

        $product2 = Product::create([
            'name' => 'Toyota Veloz Hybrid 2023',
            'price' => 32000000
        ]);

        // Buat Dummy Data Variant untuk Product 1 (Honda HR-V)
        Variant::create([
            'product_id' => $product1->id,
            'name' => 'Model High-End / RS Turbo',
            'description' => 'Edisi tertinggi dengan fitur keselamatan Honda Sensing penuh.',
            'mesin' => '1.5L VTEC Turbo Engine',
            'fitur' => 'Premium Leather | Panoramic Glass Roof',
        ]);

        Variant::create([
            'product_id' => $product1->id,
            'name' => 'Model Standar / SE CVT',
            'description' => 'Edisi standar untuk harian.',
            'mesin' => '1.5L DOHC i-VTEC',
            'fitur' => 'Fabric & Leather | 8" Capacitive Touchscreen',
        ]);

        // Buat Dummy Data Variant untuk Product 2 (Toyota Veloz)
        Variant::create([
            'product_id' => $product2->id,
            'name' => 'Model Base / Q CVT TSS',
            'description' => 'Edisi dasar dengan perlengkapan Toyota Safety Sense.',
            'mesin' => '1.5L Dual VVT-i',
            'fitur' => 'Fabric & Synthetic Leather | Wireless Charger',
        ]);
    }
}
