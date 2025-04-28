<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //permission for permissions
        Permission::create(['name' => 'permissions.index', 'guard_name' => 'api']);

        //permission for roles
        Permission::create(['name' => 'roles.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'api']);

        //permission for categories
        Permission::create(['name' => 'categories.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'categories.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'categories.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'categories.delete', 'guard_name' => 'api']);

        //permission for users
        Permission::create(['name' => 'users.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.delete', 'guard_name' => 'api']);

        //permission for products
        Permission::create(['name' => 'products.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'products.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'products.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'products.delete', 'guard_name' => 'api']);
        
        //permission for suppliers
        Permission::create(['name' => 'suppliers.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'suppliers.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'suppliers.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'suppliers.delete', 'guard_name' => 'api']);

        //permission for stock
        Permission::create(['name' => 'stock.in', 'guard_name' => 'api']);
        Permission::create(['name' => 'stock.out', 'guard_name' => 'api']);
    }
}
