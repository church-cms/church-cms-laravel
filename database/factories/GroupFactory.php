<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition(): array
    {
        $this->faker->addProvider(new \Faker\Provider\en_US\Text($this->faker));

        return [
            'category_id' => $this->faker->numberBetween(1, 7),
            'name'        => $this->faker->sentence(3),
            'cover_image' => $this->faker->imageUrl(640, 480),
            'description' => $this->faker->text,
            'group_type'  => $this->faker->randomElement([
                'common_interests', 'everyone', 'married_couples',
                'men', 'women', 'young_adults', 'youth',
            ]),
        ];
    }
}
