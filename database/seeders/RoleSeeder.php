<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => Roles::ADMIN->value]);
        Role::create(['name' => Roles::SUPER_ADMIN->value]);
        Role::create(['name' => Roles::INSTITUTION->value]);
        Role::create(['name' => Roles::USER->value]);
        Role::create(['name' => Roles::GUEST->value]);
    }
}
