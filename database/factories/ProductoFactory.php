<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
			'codigo' => $this->faker->name,
			'nombre' => $this->faker->name,
			'costo' => $this->faker->name,
			'porcentage_ganancia' => $this->faker->name,
			'precio_venta' => $this->faker->name,
			'gravable' => $this->faker->name,
			'estatus' => $this->faker->name,
        ];
    }
}
