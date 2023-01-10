<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Roles
        /*
         Role::create(['name' => 'Admin']);
         Role::create(['name' => 'Moderator']);
         Role::create(['name' => 'User']);
        */


        //Give role to model
        /*
        $user = User::where('id', 1)->first();
        $user->syncRoles('Admin');
        */
    }
}
