<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prayer;
use App\Models\PrayerParticipant;
use Exception;
use Log;

class CheckPrayerCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkprayercleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete rejected prayers whose should_delete_at TTL has passed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $count = 0;

            Prayer::shouldDelete()->chunk(50, function ($prayers) use (&$count) {
                foreach ($prayers as $prayer) {
                    $prayerId = $prayer->id;

                    PrayerParticipant::where('prayer_id', $prayerId)->delete();

                    Log::info('CheckPrayerCleanup: hard-deleting prayer #' . $prayerId);
                    $prayer->forceDelete();

                    $count++;
                }
            });

            Log::info('CheckPrayerCleanup: deleted ' . $count . ' rejected prayers');
            $this->info('Deleted ' . $count . ' rejected prayers.');
        } catch (Exception $e) {
            Log::error('CheckPrayerCleanup error: ' . $e->getMessage());
        }

        return 0;
    }
}
