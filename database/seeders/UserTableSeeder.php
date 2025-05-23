<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@dev.com',
            'password' => bcrypt('password'),
        ]);

       //assign permission to role
       $role = Role::find(1);
       $permissions = Permission::all();

       $role->syncPermissions($permissions);

       //assign role with permission to user
       $user = User::find(1);
       $user->assignRole($role->name);
    }
}
