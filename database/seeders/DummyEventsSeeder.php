<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\User;
use App\Models\Events;
use App\Models\EventGallery;
use Carbon\Carbon;

class DummyEventsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Culturals', 'Education', 'Meeting', 'prayer', 'sermon'];

        $organisers = [
            'Pastor Thomas', 'Deacon Samuel', 'Sister Maria', 'Brother Paul',
            'Youth Ministry', "Women's Fellowship", 'Music Ministry', 'Bible Study Group',
        ];

        $locations = [
            'Main Hall', 'Chapel', 'Conference Room A', 'Youth Centre',
            'Prayer Garden', 'Parish Hall', 'Library Room', 'Sanctuary',
        ];

        $titleTemplates = [
            'Culturals' => [
                'Annual Cultural Fest', 'Christmas Celebration', 'Easter Sunday Service',
                'Harvest Festival', 'Thanksgiving Gathering', 'New Year Prayer Service',
            ],
            'Education' => [
                'Sunday Bible Class', 'Youth Catechism Session', 'Scripture Study Workshop',
                'Faith Formation Class', 'Confirmation Retreat', 'Alpha Course Session',
            ],
            'Meeting'   => [
                'Parish Council Meeting', 'Finance Committee Review', 'Liturgy Planning Meeting',
                'Volunteer Coordination', 'Annual General Meeting', 'Ministry Leaders Meeting',
            ],
            'prayer'    => [
                'Morning Prayer Service', 'Evening Rosary', 'Intercessory Prayer Night',
                'Healing Prayer Mass', 'First Friday Adoration', 'Lenten Prayer Service',
            ],
            'sermon'    => [
                'Sunday Homily', 'Guest Preacher Series', 'Lenten Reflection Talk',
                'Mission Sunday Sermon', 'Youth Retreat Talk', "Men's Breakfast Sermon",
            ],
        ];

        $churches = Church::where('status', 1)->get();

        foreach ($churches as $church) {
            $admin = User::where('church_id', $church->id)
                         ->whereIn('usergroup_id', [1, 2, 3])
                         ->first() ?? User::where('church_id', $church->id)->first();

            if (! $admin) {
                continue;
            }

            $count      = 30;
            $now        = Carbon::now();
            $startRange = $now->copy()->subMonths(20);
            $totalDays  = $startRange->diffInDays($now->copy()->subDay());

            $this->command->info("Seeding {$count} events for church: {$church->name}...");

            for ($i = 0; $i < $count; $i++) {
                $daysOffset = (int) round(($i / $count) * $totalDays);
                $startDate  = $startRange->copy()->addDays($daysOffset)
                                ->setTime(rand(7, 19), [0, 15, 30, 45][rand(0, 3)], 0);
                $endDate    = $startDate->copy()->addHours(rand(1, 3));

                $category = $categories[$i % count($categories)];
                $titles   = $titleTemplates[$category];
                $title    = $titles[$i % count($titles)] . ' ' . $startDate->format('M Y');

                $event = Events::create([
                    'church_id'    => $church->id,
                    'select_type'  => 'public',
                    'title'        => $title,
                    'description'  => "Parish community event: {$title}.",
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
                    'created_by'   => $admin->id,
                    'updated_by'   => $admin->id,
                ]);

                EventGallery::factory()->count(rand(5, 15))->create([
                    'event_id'   => $event->id,
                    'church_id'  => $church->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
            }
        }

        $this->command->info('Done.');
    }
}
