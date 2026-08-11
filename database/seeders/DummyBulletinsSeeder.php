<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bulletin;
use App\Models\Church;

class DummyBulletinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $churches = Church::where('status', 1)->get();

        foreach ($churches as $church) {
            Bulletin::factory()->count(6)->create([
                'church_id' => $church->id,
            ]);
        }
    }
}
