<?php

namespace Database\Factories;

use App\Models\Bulletin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulletinFactory extends Factory
{
    use HasFactory;
    protected $model = Bulletin::class;

    public function definition(): array
    {
        $type  = $this->faker->randomElement(['month', 'week']);
        $year  = $this->faker->numberBetween(2018, 2026);

        if ($type === 'week') {
            $week  = $this->faker->numberBetween(1, 52);
            $month = null;
            $name  = "Weekly Bulletin – Week {$week}, {$year}";
        } else {
            $week  = null;
            $month = $this->faker->numberBetween(1, 12);
            $name  = "Monthly Bulletin – " . \Carbon\Carbon::create($year, $month)->format('F Y');
        }

        $paths = [
            'uploads/Images/Bulletins/bullet.jpg',
            'uploads/Images/Bulletins/bullet1.jpg',
            'uploads/Images/Bulletins/bullet2.jpg',
            'uploads/Images/Bulletins/bullet3.jpg',
            'uploads/Images/Bulletins/bullet4.jpg',
            'uploads/Images/Bulletins/bullet5.jpg',
        ];

        return [
            'name'        => $name,
            'cover_image' => url($paths[array_rand($paths)]),
            'type'        => $type,
            'week'        => $week,
            'month'       => $month,
            'year'        => $year,
            'path'        => 'uploads/bulletins/bulletin_' . $this->faker->lexify('??????') . '.pdf',
        ];
    }
}
