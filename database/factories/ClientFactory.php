<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipoidentificacion' => 'CÉDULA DE CIUDADANÍA',
            'nombres' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'numidentificacion' => $this->faker->unique()->numerify('########'),
            'email' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->numerify('9########'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
