<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Church;
use App\Models\MediaFile;
use App\Models\User;

class DummyMediaFilesSeeder extends Seeder
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
            $admin = User::where([['church_id',$church->id],['usergroup_id',3]])->first();
            
            MediaFile::factory()->count(10)->create([
                'church_id'  => $church->id,
                'created_by' => $admin->id, 
                'updated_by' => $admin->id
            ]);
        }
    }
}