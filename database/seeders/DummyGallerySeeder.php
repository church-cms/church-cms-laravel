<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\Gallery;
use App\Models\Photos;
use App\Models\User;

class DummyGallerySeeder extends Seeder
{
    public function run(): void
    {
        $churches = Church::where('status', 1)->get();

        foreach ($churches as $church) {
            $admin = User::where('church_id', $church->id)
                         ->whereIn('usergroup_id', [1, 2, 3])
                         ->first() ?? User::where('church_id', $church->id)->first();

            if (! $admin) {
                continue;
            }

            $this->command->info("Seeding galleries for church: {$church->name}...");

            Gallery::factory()->count(8)->create([
                'church_id'  => $church->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ])->each(function ($gallery) use ($church, $admin) {
                Photos::factory()->count(rand(8, 20))->create([
                    'gallery_id' => $gallery->id,
                    'church_id'  => $church->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
            });
        }

        $this->command->info('Done.');
    }
}
