<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $dataPermissions = [
            'create',
            'read',
            'update',
            'delete',
        ];

        foreach ($dataPermissions as $dp) {
            Permission::updateOrCreate(['name'=>$dp],['name'=> $dp]);
        }


    }
}
