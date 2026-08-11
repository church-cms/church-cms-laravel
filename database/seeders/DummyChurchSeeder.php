<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\Role;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Seeder;

class DummyChurchSeeder extends Seeder
{
    public function run()
    {
        $church = Church::where('status', 1)->first();

        if (! $church) {
            $this->command->error('No active church found. Run `php artisan church:setup` first.');
            return;
        }

        $subadminRole = Role::where('name', 'church-subadmin')->first();
        $staffRole    = Role::where('name', 'staff')->first();

        // 1 Sub-admin
        //User::factory()->count(10)->create();
        User::factory()->count(1)->create([
            'email'        => 'subadmin@mailinator.com',
            'church_id'    => $church->id,
            'usergroup_id' => 4,
        ])->each(function ($user) use ($subadminRole) {

            Userprofile::factory()->count(1)->create([
                'user_id'         => $user->id,
                'church_id'       => $user->church_id,
                'membership_type' => 'member',
                'status'          => 'active',
            ]);
            if ($subadminRole) {
                $user->attachRole($subadminRole);
            }
        });

        // 6 Staff
        User::factory()->count(6)->create([
            'church_id'    => $church->id,
            'usergroup_id' => 4,
        ])->each(function ($staff) use ($staffRole) {
            Userprofile::factory()->count(1)->create([
                'user_id'         => $staff->id,
                'church_id'       => $staff->church_id,
                'membership_type' => 'member',
                'status'          => 'active',
            ]);
            if ($staffRole) {
                $staff->attachRole($staffRole);
            }
        });

        // 100 Members
        User::factory()->count(100)->create([
            'church_id'    => $church->id,
            'usergroup_id' => 5,
        ])->each(function ($member) {
            Userprofile::factory()->count(1)->create([
                'user_id'         => $member->id,
                'church_id'       => $member->church_id,
                'membership_type' => 'member',
                'status'          => 'active',
            ]);
        });

        // 20 Guests
        User::factory()->count(20)->create([
            'church_id'    => $church->id,
            'usergroup_id' => 5,
        ])->each(function ($guest) {
            Userprofile::factory()->count(1)->create([
                'user_id'         => $guest->id,
                'church_id'       => $guest->church_id,
                'membership_type' => 'guest',
                'status'          => 'active',
            ]);
        });
    }
}
