<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles first
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'provider']);
        Role::create(['name' => 'customer']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Create provider user
        $provider = User::create([
            'name' => 'John Provider',
            'email' => 'provider@test.com',
            'password' => bcrypt('password'),
        ]);
        $provider->assignRole('provider');

        // Create customer user
        $customer = User::create([
            'name' => 'Jane Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
        ]);
        $customer->assignRole('customer');
    }
}
