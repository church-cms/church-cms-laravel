<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = fake()->unique()->userName();

        return [
            'name'                    => $name,
            'email'                   => $name . '@mailinator.com',
            'mobile_no'               => fake()->unique()->numerify('#########'),
            'password'                => bcrypt('password'),
            'email_verification_code' => Str::random(40),
            'remember_token'          => Str::random(10),
        ];
    }
}