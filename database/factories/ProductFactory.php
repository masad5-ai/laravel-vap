<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 9, 49);
        $compare = $price + $this->faker->randomFloat(2, 5, 20);

        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name.'-'.$this->faker->unique()->numerify('###')),
            'sku' => strtoupper($this->faker->bothify('VAP-###??')),
            'brand' => $this->faker->randomElement(['Vaperoo', 'Uncle V', 'ViceClouds']),
            'hero_image' => $this->faker->imageUrl(800, 800, 'vape', true),
            'thumbnail' => $this->faker->imageUrl(400, 400, 'vape', true),
            'nicotine_strength' => $this->faker->randomElement(['0mg', '3mg', '6mg', '12mg']),
            'flavour_profile' => $this->faker->randomElement(['Menthol', 'Fruit', 'Dessert', 'Tobacco']),
            'short_description' => $this->faker->sentence(18),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $price,
            'compare_at_price' => $compare,
            'stock' => $this->faker->numberBetween(5, 150),
            'safety_stock' => 5,
            'is_active' => true,
            'is_featured' => $this->faker->boolean(35),
            'attributes' => [
                'pg_vg_ratio' => $this->faker->randomElement(['50/50', '70/30', '80/20']),
                'bottle_size' => $this->faker->randomElement(['30ml', '60ml', '100ml']),
            ],
            'metadata' => [
                'seo_title' => ucwords($name).' | Premium Vape',
            ],
        ];
    }
}
