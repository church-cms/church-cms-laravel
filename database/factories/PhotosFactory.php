<?php

namespace Database\Factories;

use App\Models\Photos;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotosFactory extends Factory
{
    protected $model = Photos::class;

    public function definition(): array
    {
        return [
            'path' => 'uploads/galleries/photos/' . $this->faker->lexify('????????') . '.jpg',
        ];
    }
}
