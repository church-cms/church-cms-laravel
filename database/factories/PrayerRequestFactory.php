<?php

namespace Database\Factories;

use App\Models\PrayerRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrayerRequestFactory extends Factory
{
    protected $model = PrayerRequest::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->realText(rand(10, 20)),
            'description' => $this->faker->text,
            'date'        => $this->faker->dateTimeThisYear(),
        ];
    }
}
