<?php

namespace Database\Factories;

use App\Models\Tipospago;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipospagoFactory extends Factory
{
    protected $model = Tipospago::class;

    public function definition()
    {
        return [
			'tipo' => $this->faker->name,
			'estatus' => $this->faker->name,
        ];
    }
}
