<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calls>
 */
class CallsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'area'=>'BBC',
            'empresa'=>$this->faker->sentence(),
            'fecha'=>$this->faker->sentence(),
            'telefono'=>$this->faker->sentence(),
            'comentario'=>$this->faker->sentence(),
            'responsable'=>'Esrom Obed Andrade Ortiz',
            'estado'=>'Activo',
        ];
    }
}
