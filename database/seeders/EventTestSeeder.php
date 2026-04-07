<?php

namespace Database\Seeders;

use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * EventTestSeeder
 *
 * Seeds 100 past events spread across the last 20 months.
 * All events are public, have realistic durations (1–3 hours), and cover
 * a variety of categories and locations.
 *
 * Run:  php artisan db:seed --class=EventTestSeeder
 * Safe to re-run – it does NOT clear existing events first.
 */
class EventTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereIn('usergroup_id', [1, 2])->first() ?? User::first();

        if (! $admin) {
            $this->command->error('No user found. Run installer / church:setup first.');
            return;
        }

        $churchId = $admin->church_id;
        $userId   = $admin->id;

        $categories = ['Culturals', 'Education', 'Meeting', 'prayer', 'sermon'];

        $organisers = [
            'Pastor Thomas', 'Deacon Samuel', 'Sister Maria', 'Brother Paul',
            'Youth Ministry', 'Women\'s Fellowship', 'Music Ministry', 'Bible Study Group',
        ];

        $locations = [
            'Main Hall', 'Chapel', 'Conference Room A', 'Youth Centre',
            'Prayer Garden', 'Parish Hall', 'Library Room', 'Sanctuary',
        ];

        $titleTemplates = [
            'Culturals' => [
                'Annual Cultural Fest', 'Christmas Celebration', 'Easter Sunday Service',
                'Harvest Festival', 'Thanksgiving Gathering', 'New Year Prayer Service',
                'Feast of Assumption', 'Advent Candle Lighting',
            ],
            'Education' => [
                'Sunday Bible Class', 'Youth Catechism Session', 'Scripture Study Workshop',
                'Faith Formation Class', 'Confirmation Retreat', 'Alpha Course Session',
                'Theology Discussion', 'Children\'s Faith Education',
            ],
            'Meeting'   => [
                'Parish Council Meeting', 'Finance Committee Review', 'Liturgy Planning Meeting',
                'Volunteer Coordination', 'Annual General Meeting', 'Ministry Leaders Meeting',
                'Pastoral Team Sync', 'Building Committee Discussion',
            ],
            'prayer'    => [
                'Morning Prayer Service', 'Evening Rosary', 'Intercessory Prayer Night',
                'Healing Prayer Mass', 'First Friday Adoration', 'Lenten Prayer Service',
                'Novena to Our Lady', 'Holy Hour',
            ],
            'sermon'    => [
                'Sunday Homily', 'Guest Preacher Series', 'Lenten Reflection Talk',
                'Mission Sunday Sermon', 'Youth Retreat Talk', 'Men\'s Breakfast Sermon',
                'Women\'s Day Sermon', 'Revival Meeting',
            ],
        ];

        $now        = Carbon::now();
        $startRange = $now->copy()->subMonths(20);
        $totalDays  = $startRange->diffInDays($now->copy()->subDay()); // exclude today
        $count      = 100;

        $this->command->info("Seeding {$count} past events across 20 months for church_id={$churchId}...");

        for ($i = 0; $i < $count; $i++) {
            // Spread events uniformly across the 20-month window
            $daysOffset = (int) round(($i / $count) * $totalDays);
            $startDate  = $startRange->copy()->addDays($daysOffset)
                ->setTime(rand(7, 19), [0, 15, 30, 45][rand(0, 3)], 0);

            $durationHours = rand(1, 3);
            $endDate       = $startDate->copy()->addHours($durationHours);

            $category = $categories[$i % count($categories)];
            $titles   = $titleTemplates[$category];
            $title    = $titles[$i % count($titles)] . ' ' . $startDate->format('M Y');

            Events::create([
                'church_id'    => $churchId,
                'select_type'  => 'public',
                'title'        => $title,
                'description'  => "This is a test event: {$title}. Organised for the parish community.",
                'repeats'      => 0,
                'freq'         => null,
                'freq_term'    => null,
                'location'     => $locations[$i % count($locations)],
                'category'     => $category,
                'organised_by' => $organisers[$i % count($organisers)],
                'image'        => null,
                'start_date'   => $startDate->format('Y-m-d H:i:s'),
                'end_date'     => $endDate->format('Y-m-d H:i:s'),
                'allDay'       => 0,
                'created_by'   => $userId,
                'updated_by'   => $userId,
            ]);
        }

        $this->command->info('Done. 100 test events created.');
    }
}
