<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Listing;
use App\Models\Category;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $providerRole = Role::create(['name' => 'provider']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $provider = User::create([
            'name' => 'John Provider',
            'email' => 'provider@test.com',
            'password' => bcrypt('password'),
        ]);
        $provider->assignRole('provider');

        $customer = User::create([
            'name' => 'Jane Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
        ]);
        $customer->assignRole('customer');

        // Create categories
        $plumbing = Category::create([
            'name' => 'Plumbing',
            'slug' => 'plumbing',
            'is_active' => true,
        ]);

        $electrical = Category::create([
            'name' => 'Electrical',
            'slug' => 'electrical',
            'is_active' => true,
        ]);

        $cleaning = Category::create([
            'name' => 'Cleaning',
            'slug' => 'cleaning',
            'is_active' => true,
        ]);

        // Create 20 listings
        foreach (range(1, 20) as $i) {
            Listing::create([
                'user_id' => $provider->id,
                'category_id' => fake()->randomElement([$plumbing->id, $electrical->id, $cleaning->id]),
                'title' => fake()->jobTitle() . ' Services',
                'slug' => Str::slug(fake()->jobTitle() . ' services ' . $i),
                'description' => fake()->paragraphs(3, true),
                'city' => fake()->randomElement(['Sydney', 'Melbourne', 'Brisbane']),
                'suburb' => fake()->city(),
                'pricing_type' => fake()->randomElement(['hourly', 'fixed']),
                'price_amount' => fake()->numberBetween(50, 200),
                'status' => 'approved', // String instead of enum
            ]);
        }
    }
}
