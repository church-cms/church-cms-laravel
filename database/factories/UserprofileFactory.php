<?php

namespace Database\Factories;

use App\Models\Userprofile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class UserprofileFactory extends Factory
{
    protected $model = Userprofile::class;

    public function definition(): array
    {
        $profession = fake()->randomElement([
            'business',
            'doctor',
            'home_maker',
            'professionals',
            'self_employed',
            'student',
            'others'
        ]);

        $date_of_birth = fake()->dateTimeBetween('-30 years', '-2 years');

        $gender = fake()->randomElement(['male', 'female']);

        if ($gender == 'male') {
            $avatar = "uploads/male.png";
        } elseif ($gender == 'female') {
            $avatar = "uploads/female.png";
        } else {
            $avatar = "uploads/images.jpg";
        }

        $city_id = fake()->randomElement(['12', '24', '25', '15', '13']);

        $state_id = fake()->randomElement(['12', '24', '25', '15', '13']);

        $membership_type = fake()->randomElement(['member', 'guest']);

        $was_baptized = fake()->randomElement(['yes', 'no']);

        if ($was_baptized == 'yes') {
            $baptism_date = fake()->dateTimeBetween('-29 years', '-2 years');
        } else {
            $baptism_date = null;
        }

        if ($membership_type == 'member') {
            $membership_start_date = Carbon::now();
        } else {
            $membership_start_date = null;
        }

        return [
            'firstname'             => fake()->firstName(),
            'lastname'              => fake()->lastName(),
            'profession'            => $profession,
            'date_of_birth'         => $date_of_birth,
            'gender'                => $gender,
            'avatar'                => $avatar,
            'country_id'            => 7,
            'address'               => fake()->address(),
            'city_id'               => $city_id,
            'state_id'              => $state_id,
            'notes'                 => fake()->text(),
            'membership_type'       => $membership_type,
            'membership_start_date' => $membership_start_date,
            'was_baptized'          => $was_baptized,
            'baptism_date'          => $baptism_date,
        ];
    }
}