<?php

namespace Database\Factories;

use App\Models\Church;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ChurchFactory extends Factory
{
    protected $model = Church::class;

    public function definition(): array
    {
        $mycities = [
            ['cityname' => 31, 'state' => 24],
            ['cityname' => 24, 'state' => 24],
            ['cityname' => 32, 'state' => 24],
            ['cityname' => 12, 'state' => 12],
            ['cityname' => 15, 'state' => 15],
            ['cityname' => 33, 'state' => 15],
        ];

        $mycity = Arr::random($mycities);

        return [
            'address'  => $this->faker->address,
            'city_id'  => $mycity['cityname'],
            'state_id' => $mycity['state'],
            'pincode'  => $this->faker->postcode,
        ];
    }
}
