<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\EventGallery;
use App\Models\Events;
use App\Models\User;
use Carbon\Carbon;

class DummyUpcomingEventsSeeder extends Seeder
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
            $churchadmin = User::where([['church_id',$church->id],['usergroup_id',3]])->first();
            
            Events::factory()->count(5)->create([
                'church_id'     =>  $church->id,
                'start_date'    => Carbon::now()->addDay(2),
                'end_date'      => Carbon::now()->addDay(3),
            ])->each(function($churchadmin){

                EventGallery::factory()->count(20)->create([
                    'event_id'      =>  $churchadmin->id, 
                    'church_id'     =>  $churchadmin->church_id,
                    'created_by'    =>  $churchadmin->id, 
                    'updated_by'    =>  $churchadmin->id,
                ]);
            });
        }
    }
}