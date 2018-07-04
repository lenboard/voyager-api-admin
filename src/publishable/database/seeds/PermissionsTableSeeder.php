<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::firstOrCreate(['key' => 'browse_users', 'table_name' => 'users']);
        Permission::firstOrCreate(['key' => 'read_users', 'table_name' => 'users']);
        Permission::firstOrCreate(['key' => 'add_users', 'table_name' => 'users']);
        Permission::firstOrCreate(['key' => 'delete_users', 'table_name' => 'users']);

        Permission::generateFor('settings');
    }
}
