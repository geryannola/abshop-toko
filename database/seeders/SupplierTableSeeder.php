<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplier = array(
            [
                'nama' => 'supplier 1',
                'alamat' => 'aaa',
                'telepon' => 123
            ],
            [
                'nama' => 'supplier 2',
                'alamat' => 'aaa',
                'telepon' => 123
            ]
        );

        array_map(function (array $supplier) {
            Supplier::query()->updateOrCreate(
                $supplier
            );
        }, $supplier);
    }
}
