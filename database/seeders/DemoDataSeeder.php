<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeds a fully populated demo environment for development and testing.
 *
 * Requires core data to already be present (run DatabaseSeeder first).
 *
 * Usage:
 *   php artisan migrate:fresh --seed                         # core data only
 *   php artisan db:seed --class=DemoDataSeeder              # demo data on top
 *
 *   # Or everything in one go:
 *   php artisan migrate:fresh --seed && php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $church = \App\Models\Church::where('status', 1)->first();

        if (! $church) {
            $this->command->error('No active church found. Run `php artisan church:setup` first.');
            return;
        }

        $this->call([
            // Church & users (foundation for all other demo data)
            DummyChurchSeeder::class,
            DummyChurchDetailSeeder::class,

            // Content
            DummyEventsSeeder::class,
            DummyUpcomingEventsSeeder::class,
            DummyGallerySeeder::class,
            DummyBulletinsSeeder::class,
            DummySermonSeeder::class,
            DummySermonDataSeeder::class,
            DummyQuotesSeeder::class,
            DummyMediaFilesSeeder::class,

            // Posts & tags
            DummyPostDataSeeder::class,
            DummyTagDataSeeder::class,

            // CMS
            DummyPageCategorySeeder::class,
            DummyPageSeeder::class,
            DummyWidgetSeeder::class,

            // Community
            DummyFamilySeeder::class,
            DummyUserprofilesSeeder::class,
            DummyHelpsSeeder::class,

            // Groups & finances
            DummyGroupsSeeder::class,
            DummyFundsSeeder::class,

            // Prayer
            DummyPrayerCategorySeeder::class,
            DummyPrayerRequestsSeeder::class,
            DummyPrayerBoardSeeder::class,

            // FAQ
            DummyFaqCategorySeeder::class,
            DummyFaqDataSeeder::class,
        ]);
    }
}
