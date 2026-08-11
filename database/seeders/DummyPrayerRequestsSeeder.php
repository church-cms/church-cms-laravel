<?php
namespace Database\Seeders;

use App\Models\Church;
use App\Models\Prayer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyPrayerRequestsSeeder extends Seeder
{
    public function run()
    {
        $churches = Church::where('status', 1)->get();
        foreach ($churches as $church) {
            $userIds = User::where('church_id', $church->id)
                ->where('usergroup_id', 5)
                ->pluck('id');

            if ($userIds->isEmpty()) continue;

            Prayer::factory()->count(5)->create([
                'church_id' => $church->id,
                'user_id'   => $userIds->random(),
                'status'    => Prayer::STATUS_ACTIVE,
            ]);

            Prayer::factory()->count(2)->create([
                'church_id' => $church->id,
                'user_id'   => $userIds->random(),
                'status'    => Prayer::STATUS_ANSWERED,
            ]);

            Prayer::factory()->count(3)->create([
                'church_id' => $church->id,
                'user_id'   => $userIds->random(),
                'status'    => Prayer::STATUS_PENDING,
            ]);
        }
    }
}
