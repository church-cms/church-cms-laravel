<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\User;
use App\Models\Sermon;
use App\Models\SermonLink;

class DummySermonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $churchs = Church::where('status',1)->get();
        foreach ($churchs as $church)
        {
            $author = User::where('church_id', $church->id)
                ->whereIn('usergroup_id', [6, 3, 4])
                ->first()
                ?? User::where('church_id', $church->id)->first();

            if (! $author) continue;

            Sermon::factory()->count(3)->create([
                'church_id' => $church->id,
                'user_id'   => $author->id,
            ])->each(function ($sermon) use ($author) {
                SermonLink::factory()->count(5)->create([
                    'church_id'  => $author->church_id,
                    'user_id'    => $author->id,
                    'sermons_id' => $sermon->id,
                ]);
            });
        }
    }
}
