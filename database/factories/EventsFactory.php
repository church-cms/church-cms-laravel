<?php

namespace Database\Factories;

use App\Models\Events;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventsFactory extends Factory
{
    protected $model = Events::class;

    public function definition(): array
    {
        $title        = $this->faker->randomElement(['Bday', 'Prayer', 'Meeting', 'Conference', 'Culturals', 'Marriage', 'Mass']);
        $select_type  = 'public';
        $description  = $this->faker->text;
        $location     = $this->faker->randomElement(['Amaravati', 'Bengaluru', 'Thiruvananthapuram', 'Chennai', 'Hyderabad', 'Mumbai']);
        $category     = $this->faker->randomElement(['Prayer', 'Meeting', 'Culturals', 'Education']);
        $organised_by = $this->faker->name;
        $repeats      = $this->faker->numberBetween(0, 1);
        $freq         = null;
        $freq_term    = null;
        $start_date   = Carbon::now();
        $end_date     = Carbon::now()->addHours(2);

        if ($repeats == 1) {
            $freq_term  = $this->faker->randomElement(['week', 'day', 'month', 'year']);
            $start_date = Carbon::now();

            if ($freq_term === 'day') {
                $freq     = $this->faker->numberBetween(2, 30);
                $end_date = $this->faker->dateTimeInInterval('+' . $freq . ' days');
            } elseif ($freq_term === 'week') {
                $freq     = $this->faker->numberBetween(1, 4);
                $end_date = $this->faker->dateTimeInInterval('+' . $freq . ' week');
            } elseif ($freq_term === 'month') {
                $freq     = $this->faker->numberBetween(1, 12);
                $end_date = $this->faker->dateTimeInInterval('+' . $freq . ' month');
            } else {
                $freq     = $this->faker->numberBetween(1, 2);
                $end_date = $this->faker->dateTimeInInterval('+' . $freq . ' year');
            }
        }

        return [
            'select_type'  => $select_type,
            'title'        => $title,
            'description'  => $description,
            'repeats'      => $repeats,
            'freq'         => $freq,
            'freq_term'    => $freq_term,
            'location'     => $location,
            'category'     => $category,
            'organised_by' => $organised_by,
            'start_date'   => $start_date,
            'end_date'     => $end_date,
        ];
    }
}
