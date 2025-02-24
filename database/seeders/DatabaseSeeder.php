<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = RoleEnum::array();
        $permissions = PermissionEnum::array();

        foreach ($roles as $key => $value) {
            Role::create(['name' => $value]);
        }

        foreach ($permissions as $key => $value) {
            Permission::create(['name' => $value]);
        }

        Role::findByName(RoleEnum::MODERATOR->value)->givePermissionTo(Permission::all());
        Role::findByName(RoleEnum::PUBLISHER->value)->givePermissionTo(Permission::all());

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'Creator',
            'email' => 'creator@example.com',
        ]);

        User::factory()->create([
            'name' => 'Commenter',
            'email' => 'commenter@example.com',
        ]);
    }
}
