<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(8),
            'price' => $this->faker->randomFloat(2, 10, 60),
            'stock' => $this->faker->numberBetween(10, 100),
            'image_url' => $this->faker->imageUrl(800, 600, 'tech', true),
        ];
    }
}
