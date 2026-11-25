<?php

namespace Database\Factories;

use App\Models\EventGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventGalleryFactory extends Factory
{
    protected $model = EventGallery::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(640, 480),
        ];
    }
}
