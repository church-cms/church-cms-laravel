<?php

namespace Database\Seeders;

use App\Models\Prayer;
use App\Models\PrayerCategory;
use App\Models\PrayerParticipant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * PrayerBoardTestSeeder
 *
 * Seeds realistic test data across all 6 prayer statuses so every admin tab
 * has visible data. Requires the PrayerBoardSeeder (categories + settings)
 * to have been run first.
 *
 * Run:  php artisan db:seed --class=PrayerBoardTestSeeder
 *
 * Safe to re-run — adds on top of existing data.
 */
class PrayerBoardTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereIn('usergroup_id', [1, 2])->first() ?? User::first();

        if (! $admin) {
            $this->command->error('No user found. Run the installer first.');
            return;
        }

        $churchId = $admin->church_id;

        $categories = PrayerCategory::forChurch($churchId)->active()->ordered()->get();

        if ($categories->isEmpty()) {
            $this->command->error('No prayer categories found. Run PrayerBoardSeeder first.');
            return;
        }

        // Collect member users to act as submitters & participants
        $members = User::where('church_id', $churchId)->pluck('id')->toArray();
        if (empty($members)) {
            $members = [$admin->id];
        }

        $this->command->info("Seeding prayer board test data for church_id={$churchId}...");

        $created = [
            'pending'     => 0,
            'active'      => 0,
            'answered'    => 0,
            'ended'       => 0,
            'rejected'    => 0,
            'unpublished' => 0,
        ];

        // ── PENDING (8 prayers) ──────────────────────────────────────────────
        for ($i = 0; $i < 8; $i++) {
            factory(Prayer::class)->states('pending')->create([
                'church_id'   => $churchId,
                'category_id' => $categories->random()->id,
                'user_id'     => $this->pick($members),
            ]);
            $created['pending']++;
        }

        // ── ACTIVE (12 prayers, 2 pinned) ────────────────────────────────────
        $activePrayers = collect();
        for ($i = 0; $i < 12; $i++) {
            $state  = ($i < 2) ? 'pinned' : 'active';
            $userId = $this->pick($members);

            $prayer = factory(Prayer::class)->states($state)->create([
                'church_id'   => $churchId,
                'category_id' => $categories->random()->id,
                'user_id'     => $userId,
                'approved_by' => $admin->id,
                'pinned_by'   => ($i < 2) ? $admin->id : null,
            ]);

            $activePrayers->push($prayer);
            $created['active']++;
        }

        // Seed participants for active prayers
        $this->seedParticipants($activePrayers, $members, $churchId);

        // ── ANSWERED (6 prayers) ─────────────────────────────────────────────
        $answeredPrayers = collect();
        for ($i = 0; $i < 6; $i++) {
            $prayer = factory(Prayer::class)->states('answered')->create([
                'church_id'   => $churchId,
                'category_id' => $categories->random()->id,
                'user_id'     => $this->pick($members),
                'approved_by' => $admin->id,
                'answered_by' => $admin->id,
            ]);
            $answeredPrayers->push($prayer);
            $created['answered']++;
        }

        $this->seedParticipants($answeredPrayers, $members, $churchId);

        // ── ENDED (6 prayers) ────────────────────────────────────────────────
        $endedPrayers = collect();
        for ($i = 0; $i < 6; $i++) {
            $prayer = factory(Prayer::class)->states('ended')->create([
                'church_id'   => $churchId,
                'category_id' => $categories->random()->id,
                'user_id'     => $this->pick($members),
                'approved_by' => $admin->id,
            ]);
            $endedPrayers->push($prayer);
            $created['ended']++;
        }

        $this->seedParticipants($endedPrayers, $members, $churchId);

        // ── REJECTED (4 prayers) ─────────────────────────────────────────────
        for ($i = 0; $i < 4; $i++) {
            factory(Prayer::class)->states('rejected')->create([
                'church_id'   => $churchId,
                'category_id' => $categories->random()->id,
                'user_id'     => $this->pick($members),
                'rejected_by' => $admin->id,
            ]);
            $created['rejected']++;
        }

        // ── UNPUBLISHED (3 prayers) ──────────────────────────────────────────
        for ($i = 0; $i < 3; $i++) {
            factory(Prayer::class)->states('unpublished')->create([
                'church_id'   => $churchId,
                'category_id' => $categories->random()->id,
                'user_id'     => $this->pick($members),
                'approved_by' => $admin->id,
            ]);
            $created['unpublished']++;
        }

        $this->command->table(
            ['Status', 'Count'],
            collect($created)->map(fn($count, $status) => [strtoupper($status), $count])->values()->all()
        );

        $total = array_sum($created);
        $this->command->info("Done! Created {$total} test prayers.");
    }

    /**
     * Seed PrayerParticipant rows for a collection of prayers.
     * Participant counts on the prayer rows were set by the factory states;
     * this creates actual participant records so the detail view is populated.
     */
    private function seedParticipants($prayers, array $memberIds, int $churchId): void
    {
        foreach ($prayers as $prayer) {
            $usedUserIds = [];

            // Member participants
            $memberCount = min($prayer->member_count, count($memberIds));
            $shuffled    = $memberIds;
            shuffle($shuffled);

            foreach (array_slice($shuffled, 0, $memberCount) as $uid) {
                if ($uid === $prayer->user_id) {
                    continue; // submitter doesn't participate in their own
                }
                $usedUserIds[] = $uid;
                DB::table('prayer_participants')->insertOrIgnore([
                    'church_id'        => $churchId,
                    'prayer_id'        => $prayer->id,
                    'user_id'          => $uid,
                    'participant_type' => PrayerParticipant::TYPE_MEMBER,
                    'anon_hash'        => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }

            // Anonymous participants
            for ($a = 0; $a < $prayer->anonymous_count; $a++) {
                $hash = hash('sha256', 'anon-' . $prayer->id . '-' . $a);
                DB::table('prayer_participants')->insertOrIgnore([
                    'church_id'        => $churchId,
                    'prayer_id'        => $prayer->id,
                    'user_id'          => null,
                    'participant_type' => PrayerParticipant::TYPE_ANONYMOUS,
                    'anon_hash'        => $hash,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }

    /** Pick a random item from an array. */
    private function pick(array $items)
    {
        return $items[array_rand($items)];
    }
}
