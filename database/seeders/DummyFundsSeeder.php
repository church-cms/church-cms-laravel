<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\Fund;
use App\Models\User;

class DummyFundsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $churchs = Church::where('status',1)->get();
        foreach ($churchs as $church) 
        {
            $admin = User::where([['church_id',$church->id],['usergroup_id',3]])->first();
            Fund::factory()->count(20)->create([
                'church_id'     =>  $church->id,
                'authorised_by' =>  $admin->id,
                'status'        =>  'deposited',
            ]);
        }
    }
}