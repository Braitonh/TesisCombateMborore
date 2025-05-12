<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'precio' => $this->faker->randomFloat(2, 10, 100),
            'activo' => $this->faker->boolean,
            'imagen' => $this->faker->imageUrl(),
            'categoria' => $this->faker->randomElement(['hamburguesa', 'pizza', 'bebidas']),
            'posicion' => $this->faker->numberBetween(1, 100),
        ];
    }
}
