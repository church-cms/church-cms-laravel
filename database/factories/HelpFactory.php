<?php

namespace Database\Factories;

use App\Models\Help;
use Illuminate\Database\Eloquent\Factories\Factory;

class HelpFactory extends Factory
{
    protected $model = Help::class;

    public function definition(): array
    {
        return [
            'title'           => $this->faker->realText(rand(10, 20)),
            'description'     => $this->faker->text,
            'contact_details' => $this->faker->unique()->randomNumber(9, false),
        ];
    }
}
