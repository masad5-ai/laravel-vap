<?php

namespace Database\Seeders;

use App\Models\Product;
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
        User::factory()->create([
            'name' => 'Store Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        Product::factory()->createMany([
            [
                'name' => 'Tobacco-Free Peach Ice Disposable',
                'description' => 'Nicotine-free option inspired by popular Australian vape retailers.',
                'price' => 28.00,
                'stock' => 50,
                'image_url' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Menthol Chill Pod Pack',
                'description' => 'Cool mint flavor with smooth draw and consistent clouds.',
                'price' => 22.00,
                'stock' => 80,
                'image_url' => 'https://images.unsplash.com/photo-1604882737487-ef3c1fc2f4de?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Mixed Fruit Sampler',
                'description' => 'Bundle of best-selling fruit-inspired disposables for discovery shopping.',
                'price' => 35.00,
                'stock' => 40,
                'image_url' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5d8d9?auto=format&fit=crop&w=800&q=80',
            ],
        ]);
    }
}
