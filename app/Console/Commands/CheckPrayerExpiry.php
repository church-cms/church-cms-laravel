<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prayer;
use Exception;
use Log;

class CheckPrayerExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkprayerexpiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire ACTIVE prayers whose expiry date has passed — marks them ENDED';

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

            Prayer::shouldExpire()->chunk(50, function ($prayers) use (&$count) {
                foreach ($prayers as $prayer) {
                    $prayer->status = Prayer::STATUS_ENDED;
                    $prayer->save();

                    activity()
                        ->performedOn($prayer)
                        ->useLog('prayer')
                        ->withProperties(['previous_status' => Prayer::STATUS_ACTIVE])
                        ->log('Prayer automatically expired (scheduled task)');

                    $count++;
                }
            });

            Log::info('CheckPrayerExpiry: expired ' . $count . ' prayers');
            $this->info('Expired ' . $count . ' prayers.');
        } catch (Exception $e) {
            Log::error('CheckPrayerExpiry error: ' . $e->getMessage());
        }

        return 0;
    }
}
