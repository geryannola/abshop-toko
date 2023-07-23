<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = array(
            [
                'kode_member' => '00001',
                'nama' => 'Member 1',
                'alamat' => 'aaa',
                'telepon' => 123
            ],
            [
                'kode_member' => '00002',
                'nama' => 'Member 2',
                'alamat' => 'aaa',
                'telepon' => 123
            ]
        );

        array_map(function (array $members) {
            Member::query()->updateOrCreate(
                $members
            );
        }, $members);
    }
}
