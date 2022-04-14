<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
                [
                    'name'=>'Kepala Distrik Navigasi',
                    'type'=> 3,

                ],
                [
                    'name'=> 'Kabag Tata Usaha',
                    'type'=> 3,

                ],
                [
                    'name'=> 'Kabid Operasi',
                    'type'=> 3,

                ],
                [
                    'name'=> 'Kabid Logistik',
                    'type'=> 3,

                ],
                [
                    'name'=> 'Kasie Pengadaan',
                    'type'=> 1,

                ],
                [
                    'name'=> 'Staff Seksi Pengadaan',
                    'type'=> 1,

                ],
                [
                    'name'=> 'Bendahara Materil',
                    'type'=> 1,

                ],
                [
                    'name'=> 'Pengelola Gudang',
                    'type'=> 1,

                ],
                [
                    'name'=> 'Kurir/Offsetter',
                    'type'=> 1,

                ],
                [
                    'name'=> 'Seksi Pengadaan',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Seksi Inventaris',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Seksi Program',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Seksi Sarana Prasarana',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Subbag Kepegawaian dan Umum',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Subbag Keuangan',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Nakhoda',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Kepala VTS',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Kepala SROP',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Kepala Kelompok Pengamatan Laut',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Kepala Kelompok Bengkel',
                    'type'=> 2,

                ],
                [
                    'name'=> 'Kepala Kelompok SBNP',
                    'type'=> 2,

                ],
        ];

        foreach ($roles as $key => $value) {
            # code...
            // ModelsRole::insert($roles);
            Role::create($value);
        }

    }
}
