<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    { 
    $adminRole = \App\Models\Role::create(['name' => 'admin']);
    $modRole = \App\Models\Role::create(['name' => 'moderator']);
    $userRole = \App\Models\Role::create(['name' => 'user']);
 
    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => bcrypt('123'),
        'role_id' => $adminRole->id,
    ]);
 
    \App\Models\User::create([
        'name' => 'Moderator',
        'email' => 'mod@mod.com',
        'password' => bcrypt('123'),
        'role_id' => $modRole->id,
    ]);
 
    \App\Models\User::create([
        'name' => 'User',
        'email' => 'user@user.com',
        'password' => bcrypt('123'),
        'role_id' => $userRole->id,
    ]);
    }
}
