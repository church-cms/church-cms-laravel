<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\User;
use App\Models\Help;

class DummyHelpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $churches = Church::where('status',1)->get();
        foreach ($churches as $church)
        {
            $users = User::where([['church_id',$church->id],['usergroup_id',5]])->pluck('id')->toArray();

            $user = array_rand($users);

            Help::factory()->count(2)->create([
                'church_id'	=> 	$church->id,
                'user_id'   =>	$user,
                'status' 	=> 	'approve',
            ]);
        }
    }
}
