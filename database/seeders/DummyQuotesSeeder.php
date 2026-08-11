<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Church;
use App\Models\User;
use Carbon\Carbon;

class DummyQuotesSeeder extends Seeder
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
            $admin = User::where([['church_id',$church->id],['usergroup_id',3]])->first();
            
            DB::table('quotes')->Insert([
                'church_id'         =>  $church->id,
                'user_id'           =>  $admin->id,
                'tamil_quotes'      =>  '<p>அல்லேலூயா, தேவனை அவருடைய பரிசுத்த ஸ்தலத்தில் துதியுங்கள்; அவருடைய வல்லமை விளங்கும் ஆகாய விரிவைப்பார்த்து அவரைத் துதியுங்கள்.</p>',
                'english_quotes'    =>  '<p><span style="background-color: rgb(255, 255, 255); color: rgb(9, 62, 16);">Praise The Lord . Praise God In His Sanctuary; Praise Him In His Mighty Heavens.</span></p>',
                'publish_on'        =>  Carbon::now()->addDays(1),
                'created_at'        =>  date("Y-m-d H:i:s"),
                'updated_at'        =>  date("Y-m-d H:i:s"),
            ]);

            DB::table('quotes')->Insert([
                'church_id'         =>  $church->id,
                'user_id'           =>  $admin->id,
                'image'             =>  '1/quotes/NwkdnYce7KbT4NRIlHtwUBLEKhvyKXF8JZaTuOzt.jpeg',
                'publish_on'        =>  Carbon::now()->addDays(2),
                'created_at'        =>  date("Y-m-d H:i:s"),
                'updated_at'        =>  date("Y-m-d H:i:s"),
            ]);

            DB::table('quotes')->Insert([
                'church_id'         =>  $church->id,
                'user_id'           =>  $admin->id,
                'text'              =>  '<p><span style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94);">The church is precisely that against which Jesus preached, and against which he taught his disciples to fight.</span></p>',
                'publish_on'        =>  Carbon::now()->addDays(3),
                'created_at'        =>  date("Y-m-d H:i:s"),
                'updated_at'        =>  date("Y-m-d H:i:s"),
            ]);
        }
    }
}