<?php

namespace Database\Factories;

use App\Models\MediaFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFileFactory extends Factory
{
    protected $model = MediaFile::class;

    public function definition(): array
    {
        $media_type = $this->faker->randomElement(['audio', 'video']);

        if ($media_type === 'audio') {
            $type = $this->faker->randomElement(['attach', 'record']);
        } else {
            $type = $this->faker->randomElement(['upload', 'url']);
        }

        if ($type === 'attach' || $type === 'record') {
            $url_file = $this->faker->randomElement([
                'uploads/audio/1/2jcxhvgUGJOxCnzuYScjpg516fRgBrmRQE66L1al.mp3',
                'uploads/audio/1/fJnlkF3qcrmueNd2JWcJGUOtgJwg71iI46k6ONaX.mp3',
                'uploads/audio/1/LcSTNMjvjc0PwbIDJMuvZuQdxROIJbHHchfqhAQF.mp3',
                'uploads/audio/1/6KmHtStIQsWF0AWdQTONTMrRlX3XKYm0VPASZ282.mp3',
                'uploads/audio/1/N8ifCaI5t1nlR1rUGXAZZwhEAqNcJKlmc6VN0CHv.mp3',
            ]);
        } elseif ($type === 'upload') {
            $url_file = $this->faker->randomElement([
                'uploads/video/1/17_02_2021_17_11_24_video.mp4',
                'uploads/video/1/17_02_2021_17_14_39_video.mp4',
            ]);
        } else {
            $url_file = 'https://www.youtube.com/watch?v=EngW7tLk6R8';
        }

        return [
            'media_type'  => $media_type,
            'name'        => $this->faker->realText(rand(10, 20)),
            'description' => $this->faker->text,
            'type'        => $type,
            'url'         => $url_file,
        ];
    }
}
