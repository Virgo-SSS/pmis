<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $user = User::factory()->create([
            'name' => 'Admin PMIS',
            'username' => 'admin',
            'email' => 'admin@pmis.com',
        ]);

        UserProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $role = Role::create(['name' => 'Administrator']);
        $user->assignRole($role);
    }
}
