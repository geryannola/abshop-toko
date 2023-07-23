<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategoris = array(
            [
                'nama_kategori' => 'MInyak 2 Liter'
            ],
            [
                'nama_kategori' => 'MInyak 1 Liter'
            ],
            [
                'nama_kategori' => 'Beras'
            ]
        );

        array_map(function (array $kategoris) {
            Kategori::query()->updateOrCreate(
                ['nama_kategori' => $kategoris['nama_kategori']],
                $kategoris
            );
        }, $kategoris);
    }
}
