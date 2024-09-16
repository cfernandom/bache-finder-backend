<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@localhost.com',
            'password' => Hash::make('12345678#'),
        ])->assignRole(Roles::SUPER_ADMIN->value);

        User::create([
            'name' => 'Institution',
            'email' => 'institution@localhost.com',
            'password' => Hash::make('12345678#'),
        ])->assignRole(Roles::INSTITUTION->value);

        User::create([
            'name' => 'Guest',
            'email' => 'guest@localhost.com',
            'password' => Hash::make('12345678#'),
        ])->assignRole(Roles::GUEST->value);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@localhost.com',
            'password' => Hash::make('12345678#'),
        ])->assignRole(Roles::ADMIN->value);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@localhost.com',
            'password' => Hash::make('12345678#'),
        ]);
        $user->assignRole(Roles::USER->value);

        $user = User::factory()->create([
            'name' => 'User2',
            'email' => 'user2@localhost.com',
            'password' => Hash::make('12345678#'),
        ]);

        $user->assignRole(Roles::USER->value);
        // $user->givePermissionTo('CREATE_POTHOLES');
        // $user->givePermissionTo('READ_POTHOLES');
        // $user->givePermissionTo('UPDATE_POTHOLES');
        // $user->givePermissionTo('DELETE_POTHOLES');
    }
}
