<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        $topics = [
            'Sunday Service', 'Easter Celebration', 'Christmas Mass',
            'Baptism Ceremony', 'Youth Camp', 'Harvest Festival',
            'Parish Picnic', 'Confirmation Day', 'Mission Trip',
            'Choir Concert', 'Community Outreach', 'Thanksgiving Service',
        ];

        $paths = [
            'uploads/Images/Galleries/galleries.jpg',
            'uploads/Images/Galleries/galleries1.jpg',
            'uploads/Images/Galleries/galleries2.jpg',
            'uploads/Images/Galleries/galleries3.jpg',
            'uploads/Images/Galleries/galleries4.jpg',
            'uploads/Images/Galleries/galleries5.jpg',
            'uploads/Images/Galleries/galleries6.jpg',
            'uploads/Images/Galleries/galleries7.jpg',
            'uploads/Images/Galleries/galleries8.jpg',
            'uploads/Images/Galleries/galleries9.jpg',
        ];

        return [
            'name'        => $this->faker->randomElement($topics) . ' ' . $this->faker->year(),
            'description' => $this->faker->optional(0.7)->sentence(),
            'path'        => url($paths[array_rand($paths)]),
        ];
    }
}
