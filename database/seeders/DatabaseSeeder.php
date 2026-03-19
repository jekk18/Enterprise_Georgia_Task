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
        'name' => 'System Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role_id' => $adminRole->id,
    ]);
 
    \App\Models\User::create([
        'name' => 'Main Moderator',
        'email' => 'mod@example.com',
        'password' => bcrypt('password'),
        'role_id' => $modRole->id,
    ]);
 
    \App\Models\User::create([
        'name' => 'Regular Joe',
        'email' => 'user@example.com',
        'password' => bcrypt('password'),
        'role_id' => $userRole->id,
    ]);
    }
}
