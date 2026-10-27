<?php

namespace Database\Seeders;

use App\Models\Mobil;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobils = [
            [
                'nama' => 'Avanza 1.5 G CVT',
                'brand' => 'Toyota',
                'harga' => 275000000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
            ],
            [
                'nama' => 'Civic RS Turbo',
                'brand' => 'Honda',
                'harga' => 615000000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2',
            ],
            [
                'nama' => 'Pajero Sport Dakar 4x2',
                'brand' => 'Mitsubishi',
                'harga' => 630000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Diesel',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf',
            ],
            [
                'nama' => 'Ioniq 5 Signature Long Range',
                'brand' => 'Hyundai',
                'harga' => 850000000,
                'tahun' => 2024,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Listrik',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1563720223185-11003d516935',
            ],
            [
                'nama' => 'Innova Zenix 2.0 V Q Hybrid',
                'brand' => 'Toyota',
                'harga' => 620000000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Hybrid',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d',
            ],
            [
                'nama' => 'HR-V 1.5 SE CVT',
                'brand' => 'Honda',
                'harga' => 410000000,
                'tahun' => 2023,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'sold',
                'foto' => 'https://images.unsplash.com/photo-1590362891991-f776e747a588',
            ],
            [
                'nama' => 'Jimny 1.5 AT 4x4',
                'brand' => 'Suzuki',
                'harga' => 460000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70',
            ],
            [
                'nama' => 'Air EV Long Range',
                'brand' => 'Wuling',
                'harga' => 270000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Listrik',
                'status' => 'sold',
                'foto' => 'https://images.unsplash.com/photo-1502877338535-766e1452684a',
            ],
            [
                'nama' => '330i M Sport',
                'brand' => 'BMW',
                'harga' => 1150000000,
                'tahun' => 2024,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e',
            ],
            [
                'nama' => 'CX-5 Kuro Edition',
                'brand' => 'Mazda',
                'harga' => 610000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd',
            ],
        ];

        foreach ($mobils as $mobil) {
            Mobil::create($mobil);
        }
    }
}
