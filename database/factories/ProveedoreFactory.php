<?php

namespace Database\Factories;

use App\Models\Proveedore;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProveedoreFactory extends Factory
{
    protected $model = Proveedore::class;

    public function definition()
    {
        return [
			'rif' => $this->faker->name,
			'nombre' => $this->faker->name,
			'email' => $this->faker->name,
			'direccion' => $this->faker->name,
			'telefono' => $this->faker->name,
			'productos' => $this->faker->name,
			'estatus' => $this->faker->name,
        ];
    }
}
