<?php

namespace Modules\Users\Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeederTableSeeder extends Seeder
{
    public function run()
    {

        $permissions = [

            'tasks.view.any',
            'tasks.view.own',
            'tasks.create',
            'tasks.update.any',
            'tasks.update.own',
            'tasks.delete.any',
            'tasks.delete.own',
            'users.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'sanctum',
        ]);

        $employeeRole = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'sanctum',
        ]);




        $adminRole->givePermissionTo(Permission::all());


        $managerRole->givePermissionTo([
            'tasks.view.any',
            'tasks.create',
            'tasks.update.any',
            'tasks.delete.any',
        ]);


        $employeeRole->givePermissionTo([
            'tasks.view.own',
            'tasks.update.own',
        ]);

        $this->command->info('Roles and Permissions seeded successfully.');
    }
}
