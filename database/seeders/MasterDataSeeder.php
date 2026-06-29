<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Data Dummy Kategori
        Category::insert([
            ['nama_kategori' => 'Hardware'],
            ['nama_kategori' => 'Software'],
            ['nama_kategori' => 'Network'],
        ]);

        // Data Dummy Lokasi
        Location::insert([
            ['nama_lokasi' => 'Ruang Server'],
            ['nama_lokasi' => 'Gudang IT'],
            ['nama_lokasi' => 'Ruang Staff'],
        ]);
    }
}