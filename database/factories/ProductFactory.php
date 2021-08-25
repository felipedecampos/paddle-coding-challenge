<?php

declare(strict_types=1);

namespace Database\Factories;

use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $faker = Faker::create();

        return [
            'name' => $faker->word,
            'description' => $faker->paragraph(1),
            'qrcode' => $faker->ean13(),
            'price' => $faker->randomFloat(2, 10, 10999),
            'created_at' => now(),
            'updated_at' => null,
            'deleted_at' => null
        ];
    }

    /**
     * Indicate that the model's product should be updated.
     *
     * @return Factory
     */
    public function updated(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's product should be deleted.
     *
     * @return Factory
     */
    public function deleted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => now(),
            ];
        });
    }
}
