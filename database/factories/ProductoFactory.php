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
            'codigo_barras' => $this->faker->ean13(),
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
             // Suponiendo que tienes 5 marcas
            'categoria_id' => rand(1, 2), // Suponiendo que tienes 10 categorías
            'precio_venta' => $this->faker->randomFloat(2, 5, 100),
            'precio_compra' => $this->faker->randomFloat(2, 3, 80),
            'iva' => 12,
            'ganancia' => $this->faker->randomFloat(2, 1, 30),
            'stock' => rand(10, 200),
            'proveedor_id' => rand(1, 2), // Suponiendo que tienes 5 proveedores            
            'created_at' => now(),
            'updated_at' => now(),            
            'stock_minimo' => rand(5, 20),
        ];
    }
}
