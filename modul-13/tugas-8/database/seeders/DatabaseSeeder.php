<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun Admin Default
        User::create([
            'name' => 'Admin Honda',
            'email' => 'admin@honda.com',
            'password' => Hash::make('password'),
        ]);

        $spareparts = [
            [
                'part_number' => '06455-K81-N01',
                'name' => 'Pad Set, Front (Kampas Rem Depan)',
                'category' => 'Pengereman',
                'price' => 55000,
                'stock' => 50,
                'image_url' => 'https://www.astramotor.co.id/wp-content/uploads/2017/01/KAMPAS-REM-DEPAN-BEAT-SCOOPY-VARIO-06455K81N01.jpg',
                'description' => 'Kampas rem depan orisinil Honda untuk tipe matic (Beat, Scoopy, Vario).',
            ],
            [
                'part_number' => '31916-KRM-841',
                'name' => 'Spark Plug (Busi) CPR9EA-9',
                'category' => 'Mesin',
                'price' => 20000,
                'stock' => 100,
                'image_url' => 'https://www.astramotor.co.id/wp-content/uploads/2017/01/BUSI-CPR9EA-9-31916KRM841.jpg',
                'description' => 'Busi standar untuk Honda Vario 125/150, PCX, dan ADV.',
            ],
            [
                'part_number' => '23100-K0W-N01',
                'name' => 'Belt, Drive (V-Belt)',
                'category' => 'Transmisi',
                'price' => 185000,
                'stock' => 15,
                'image_url' => 'https://www.astramotor.co.id/wp-content/uploads/2021/03/V-BELT-ADV-150-23100K0WN01.jpg',
                'description' => 'V-Belt orisinil Honda untuk transmisi CVT motor matic.',
            ],
            [
                'part_number' => 'SPX2-1L',
                'name' => 'AHM Oil SPX 2 (1 Liter)',
                'category' => 'Oli & Pelumas',
                'price' => 65000,
                'stock' => 40,
                'image_url' => 'https://www.astramotor.co.id/wp-content/uploads/2017/01/OLI-SPX2-1L.jpg',
                'description' => 'Oli mesin sintetik full untuk performa mesin matic Honda.',
            ],
        ];

        foreach ($spareparts as $part) {
            Product::create($part);
        }
    }
}
