<?php

namespace Database\Factories;

use App\Models\Sermon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SermonFactory extends Factory
{
    protected $model = Sermon::class;

    public function definition(): array
    {


        $paths = [
            'uploads/Images/Sermons/sermons.jpg',
            'uploads/Images/Sermons/sermons1.jpg',
            'uploads/Images/Sermons/sermons2.jpg',

        ];

        return [
            'title'       => $this->faker->realText(rand(10, 20)),
            'description' => $this->faker->realText(rand(40, 50)),
            'cover_image' =>  url($paths[array_rand($paths)]),
        ];
    }
}
