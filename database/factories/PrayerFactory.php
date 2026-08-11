<?php

namespace Database\Factories;

use App\Models\Prayer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrayerFactory extends Factory
{
    protected $model = Prayer::class;

    public function definition(): array
    {
        return [
            'church_id'       => 1,
            'category_id'     => null,
            'user_id'         => null,
            'text'            => $this->faker->paragraph(rand(2, 4)),
            'status'          => Prayer::STATUS_PENDING,
            'member_count'    => 0,
            'guest_count'     => 0,
            'anonymous_count' => 0,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status'        => Prayer::STATUS_PENDING,
            'original_text' => null,
            'approved_by'   => null,
            'approved_at'   => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(function (array $attributes) {
            $expiryDays = $this->faker->randomElement([7, 14, 30, 60, 90]);
            $approvedAt = Carbon::now()->subDays(rand(1, $expiryDays - 1));

            return [
                'status'          => Prayer::STATUS_ACTIVE,
                'original_text'   => null,
                'approved_at'     => $approvedAt,
                'expiry_days'     => $expiryDays,
                'expires_at'      => $approvedAt->copy()->addDays($expiryDays),
                'member_count'    => rand(0, 50),
                'guest_count'     => rand(0, 20),
                'anonymous_count' => rand(0, 30),
            ];
        });
    }

    public function pinned(): static
    {
        return $this->state(function (array $attributes) {
            $expiryDays = $this->faker->randomElement([30, 60, 90]);
            $approvedAt = Carbon::now()->subDays(rand(1, 10));

            return [
                'status'          => Prayer::STATUS_ACTIVE,
                'original_text'   => null,
                'approved_at'     => $approvedAt,
                'expiry_days'     => $expiryDays,
                'expires_at'      => $approvedAt->copy()->addDays($expiryDays),
                'pinned_at'       => $approvedAt->copy()->addDay(),
                'member_count'    => rand(20, 100),
                'guest_count'     => rand(5, 40),
                'anonymous_count' => rand(10, 60),
            ];
        });
    }

    public function answered(): static
    {
        return $this->state(function (array $attributes) {
            $approvedAt  = Carbon::now()->subDays(rand(10, 60));
            $answeredAt  = $approvedAt->copy()->addDays(rand(5, 30));
            $testimonies = [
                'God answered this prayer in an unexpected way. We are so grateful!',
                'The situation resolved completely. Praise the Lord!',
                'We received the breakthrough we prayed for. Thank you all for standing with us.',
                'God provided beyond what we asked. All glory to Him!',
                'The healing came gradually, but it came. God is faithful.',
                null,
                null,
            ];

            return [
                'status'           => Prayer::STATUS_ANSWERED,
                'original_text'    => null,
                'approved_at'      => $approvedAt,
                'expiry_days'      => 60,
                'expires_at'       => $approvedAt->copy()->addDays(60),
                'answered_at'      => $answeredAt,
                'answer_testimony' => $this->faker->randomElement($testimonies),
                'member_count'     => rand(10, 80),
                'guest_count'      => rand(2, 30),
                'anonymous_count'  => rand(5, 40),
            ];
        });
    }

    public function ended(): static
    {
        return $this->state(function (array $attributes) {
            $expiryDays = $this->faker->randomElement([7, 14, 30, 60]);
            $approvedAt = Carbon::now()->subDays($expiryDays + rand(1, 10));

            return [
                'status'          => Prayer::STATUS_ENDED,
                'original_text'   => null,
                'approved_at'     => $approvedAt,
                'expiry_days'     => $expiryDays,
                'expires_at'      => $approvedAt->copy()->addDays($expiryDays),
                'member_count'    => rand(5, 60),
                'guest_count'     => rand(0, 20),
                'anonymous_count' => rand(0, 25),
            ];
        });
    }

    public function rejected(): static
    {
        return $this->state(function (array $attributes) {
            $reasons = [
                'This prayer request contains content that does not align with our community guidelines.',
                'This appears to be a duplicate of an existing prayer request.',
                'The request was unclear. Please resubmit with more details.',
                'This is not an appropriate prayer request for the public board.',
                'Spam or test submission.',
            ];
            $rejectedAt = Carbon::now()->subDays(rand(1, 5));

            return [
                'status'           => Prayer::STATUS_REJECTED,
                'rejection_reason' => $this->faker->randomElement($reasons),
                'rejected_at'      => $rejectedAt,
                'should_delete_at' => $rejectedAt->copy()->addDays(7),
            ];
        });
    }

    public function unpublished(): static
    {
        return $this->state(function (array $attributes) {
            $approvedAt = Carbon::now()->subDays(rand(5, 30));

            return [
                'status'          => Prayer::STATUS_UNPUBLISHED,
                'original_text'   => null,
                'approved_at'     => $approvedAt,
                'expiry_days'     => 60,
                'expires_at'      => $approvedAt->copy()->addDays(60),
                'member_count'    => rand(0, 20),
                'guest_count'     => rand(0, 10),
                'anonymous_count' => rand(0, 15),
            ];
        });
    }
}
