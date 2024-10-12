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
            'nombre' => $this->faker->unique()->words(3, true),
            'descripcion' => $this->faker->sentence(),  // Descripción aleatoria
            'precio_venta' => $this->faker->randomFloat(10, 5000), // Precio entre 10 y 5000
            'precio_compra' => $this->faker->randomFloat( 10, 5000), // Precio entre 10 y 5000
            'stock' => $this->faker->numberBetween(1, 100),

        ];
    }
}
