<?php

namespace Database\Factories;

use App\Models\SermonLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SermonLinkFactory extends Factory
{
    protected $model = SermonLink::class;

    public function definition(): array
    {
        return [
            'title'      => $this->faker->sentence(4),
            'video_link' => $this->faker->optional()->url,
            'audio_link' => $this->faker->optional()->url,
            'pdf_link'   => $this->faker->optional()->url,
            'date'       => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
