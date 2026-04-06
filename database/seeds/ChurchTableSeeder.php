<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class ChurchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $churchnames = [
            "St. Mary's Cathedral Shrine"
        ];

        //changed for demo
        /*$churchnames = [
            "Jesus blessing",
        ];*/

        /*$quotes =[
            "Psalm 23:1-3 - The Lord is my shepherd; I shall not want.He makes me lie down in green pastures.",
            "Psalm 27:1 - The Lord is my light and my salvation— whom shall I fear",
            "Psalm 34:18 - The Lord is close to the brokenhearted and saves those who are crushed in spirit.",
            "Psalm 18:2 - The Lord is my rock, my fortress and my savior.",
            "Psalm 27:14 - Wait on the Lord: be of good courage, and he shall strengthen thine heart.",
            "Psalm 34:5 - Those who look to him are radiant, and their faces shall never be ashamed.",
            "Psalm 91:4 - He will cover you with his feathers. He will shelter you with his wings.",
            "Psalm 51:10 - Create in me a pure heart, O God, and renew a steadfast spirit within me.",
            "Psalm 46:5 - God is within her, she will not fall; God will help her at break of day.",
            "Psalm 37:4 - Delight yourself in the Lord, and he will give you the desires of your heart.",
            "Psalm 46:1 - God is our refuge and strength, an ever-present help in trouble.",
            "Psalm 119:105 - Your word is a lamp for my feet, a light on my path.",
            "Psalm 46:10 - Be still and know that I am God.",
        ]; */

        $AdminPermissions = [
            "create-members","read-members","update-members",
            "create-events","read-events","update-events",
            "create-files","read-files","view-files",
            "create-bulletins","read-bulletins","view-bulletins",
            "create-gallery","read-gallery","update-gallery",
            "create-groups","read-groups","update-groups","delete-groups",
            "create-videos","read-videos","view-videos",
            "create-funds","read-funds","update-funds","view-funds",
            "create-quotes","read-quotes","update-quotes",
            "create-preachers","read-preachers","update-preachers",
            "read-reports","view-reports",
            "read-payments",'create-payments'
        ];

        $PreacherPermissions = [
            "create-sermons",
            "read-sermons",
            "update-sermons",
            "delete-sermons"
        ];

        foreach($churchnames as $churchname)
        {
            $church = factory(App\Models\Church::class)->create(['name' => strtolower($churchname) , 'slug' => Str::slug($churchname,'-')]);

            // 1 Church Admin
            factory(App\Models\User::class, 1)->create([
                'email'        => 'admin'.$church->id.'@churchcms.app',
                'church_id'    => $church->id ,
                'usergroup_id' => 3
            ])->each(function($user) use($AdminPermissions){
                factory(App\Models\Userprofile::class, 1)->create(['user_id'=>$user->id , 'church_id'=>$user->church_id , 'membership_type'=>"member"]);

                factory(App\Models\Bulletin::class, 1)->create(['church_id'=>$user->church_id ,'created_by'=>$user->id]);

                factory(App\Models\Group::class, 1)->create(['church_id'=>$user->church_id ,'created_by'=>$user->id]);

                $user->attachPermissions($AdminPermissions);
            });

            // 2 Sub-admins
            factory(App\Models\User::class, 2)->create([
                'church_id'     =>  $church->id,
                'usergroup_id'  =>  4
            ])->each(function($churchadmin){
                factory(App\Models\Userprofile::class, 1)->create([
                    'user_id'           =>  $churchadmin->id,
                    'church_id'         =>  $churchadmin->church_id,
                    'membership_type'   =>  'member',
                ]);
            });

            // 6 Staff (ChurchSubadmin)
            factory(App\Models\User::class, 6)->create([
                'church_id'     =>  $church->id,
                'usergroup_id'  =>  4
            ])->each(function($staff){
                factory(App\Models\Userprofile::class, 1)->create([
                    'user_id'           =>  $staff->id,
                    'church_id'         =>  $staff->church_id,
                    'membership_type'   =>  'member',
                ]);
            });

            // 100 Members
            $members = factory(App\Models\User::class, 100)->create([
                'church_id'     =>  $church->id ,
                'usergroup_id'  =>  5
            ]);

            foreach ($members as $member)
            {
                factory(App\Models\Userprofile::class)->create([
                    'user_id'   =>  $member->id,
                    'church_id' =>  $member->church_id
                ]);
            }

            // 3 Preachers
            factory(App\Models\User::class, 3)->create([
                'church_id'     =>  $church->id ,
                'usergroup_id'  =>  6
            ])->each(function($preacher) use($PreacherPermissions){
                factory(App\Models\Userprofile::class, 1)->create([
                    'user_id'   =>  $preacher->id ,
                    'church_id' =>  $preacher->church_id
                ]);

                $preacher->attachPermissions($PreacherPermissions);
            });
        }
    }
}
