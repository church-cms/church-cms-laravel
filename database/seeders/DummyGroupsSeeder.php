<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupLink;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DummyGroupsSeeder
 *
 * Creates 4 ministry groups with 2 admins + 4-5 members each.
 * All users: usergroup_id = 4, membership_type = member.
 *
 * Run: php artisan db:seed --class=DummyGroupsSeeder
 * Safe to re-run – uses firstOrCreate on email / group name.
 */
class DummyGroupsSeeder extends Seeder
{
    public function run()
    {
        $anchor = User::where('usergroup_id', 3)->first()
               ?? User::whereNotNull('church_id')->first();

        if (! $anchor) {
            $this->command->error('No church user found. Run church:install-data first.');
            return;
        }

        $churchId = $anchor->church_id;

        // [group_name, category_id, group_type, description, slug, members[]]
        // category_id: 1=bible_studies, 2=clubs, 3=committees, 7=others
        // Each member: [firstname, lastname, gender, dob, profession, marriage_status, role(group)]
        $groups = [
            [
                'name'        => 'Church Maintenance Group',
                'category_id' => 3,   // committees
                'group_type'  => 'common_interests',
                'description' => 'Responsible for maintaining and upkeep of church facilities and grounds.',
                'slug'        => 'maintenance',
                'members'     => [
                    ['James',   'Carter',  'male',   '1978-04-12', 'engineer',    'married',  'group_admin'],
                    ['Martha',  'Lewis',   'female', '1982-09-25', 'home_maker',  'married',  'group_admin'],
                    ['Samuel',  'Turner',  'male',   '1990-06-18', 'engineer',    'single',   'member'],
                    ['Rachel',  'Brooks',  'female', '1988-11-03', 'professionals','married', 'member'],
                    ['Henry',   'Morgan',  'male',   '1975-02-27', 'self_employed','married', 'member'],
                    ['Patricia','Reed',    'female', '1995-07-14', 'teacher',     'single',   'member'],
                ],
            ],
            [
                'name'        => 'Bible Studies Group',
                'category_id' => 1,   // bible_studies
                'group_type'  => 'common_interests',
                'description' => 'Weekly deep-dive Bible study sessions covering Old and New Testament books.',
                'slug'        => 'bible',
                'members'     => [
                    ['Daniel',  'Evans',   'male',   '1972-01-30', 'pastor',      'married',  'group_admin'],
                    ['Susan',   'Collins',  'female', '1980-05-16', 'teacher',     'married',  'group_admin'],
                    ['Andrew',  'Bell',    'male',   '1993-08-22', 'student',     'single',   'member'],
                    ['Hannah',  'Parker',  'female', '1997-03-09', 'student',     'single',   'member'],
                    ['Joseph',  'Mitchell','male',   '1985-12-01', 'business',    'married',  'member'],
                    ['Naomi',   'Stewart', 'female', '1991-06-28', 'professionals','single',  'member'],
                    ['Timothy', 'Hughes',  'male',   '1969-10-11', 'teacher',     'married',  'member'],
                ],
            ],
            [
                'name'        => 'Music Band Group',
                'category_id' => 2,   // clubs
                'group_type'  => 'common_interests',
                'description' => 'Worship music team responsible for leading congregational praise and worship.',
                'slug'        => 'music',
                'members'     => [
                    ['Michael', 'Davis',   'male',   '1987-07-04', 'others',      'married',  'group_admin'],
                    ['Angela',  'White',   'female', '1990-02-18', 'teacher',     'single',   'group_admin'],
                    ['Chris',   'Harris',  'male',   '1995-09-14', 'student',     'single',   'member'],
                    ['Laura',   'Martin',  'female', '1993-04-22', 'teacher',     'single',   'member'],
                    ['Kevin',   'Thomas',  'male',   '1988-11-30', 'engineer',    'married',  'member'],
                    ['Diana',   'Wilson',  'female', '1996-01-07', 'professionals','single',  'member'],
                ],
            ],
            [
                'name'        => 'Social Media Group',
                'category_id' => 7,   // others
                'group_type'  => 'common_interests',
                'description' => 'Manages the church\'s online presence, live streams, and social media platforms.',
                'slug'        => 'socialmedia',
                'members'     => [
                    ['Nathan',  'Clark',   'male',   '1994-03-15', 'engineer',    'single',   'group_admin'],
                    ['Jessica', 'Walker',  'female', '1992-08-20', 'others',      'single',   'group_admin'],
                    ['Ryan',    'Young',   'male',   '1998-06-11', 'student',     'single',   'member'],
                    ['Emily',   'Hall',    'female', '1999-12-03', 'student',     'single',   'member'],
                    ['Brian',   'Allen',   'male',   '1990-05-29', 'business',    'married',  'member'],
                ],
            ],
        ];

        $userCount  = 0;
        $groupCount = 0;

        foreach ($groups as $g) {
            // Create group (idempotent)
            $group = Group::firstOrCreate(
                ['church_id' => $churchId, 'name' => $g['name']],
                [
                    'category_id' => $g['category_id'],
                    'group_type'  => $g['group_type'],
                    'description' => $g['description'],
                    'created_by'  => $anchor->id,
                ]
            );

            foreach ($g['members'] as $idx => [$firstname, $lastname, $gender, $dob, $profession, $marital, $groupRole]) {
                $email = strtolower("{$firstname}.{$g['slug']}{$idx}@group.test");

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'           => "{$firstname} {$lastname}",
                        'password'       => Hash::make('password'),
                        'mobile_no'      => '09' . str_pad($userCount, 8, '0', STR_PAD_LEFT),
                        'church_id'      => $churchId,
                        'usergroup_id'   => 4,
                        'email_verified' => 1,
                    ]
                );

                Userprofile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'church_id'             => $churchId,
                        'firstname'             => $firstname,
                        'lastname'              => $lastname,
                        'gender'                => $gender,
                        'date_of_birth'         => $dob,
                        'profession'            => $profession,
                        'marriage_status'       => $marital,
                        'was_baptized'          => 'yes',
                        'baptism_date'          => '2015-01-01',
                        'membership_type'       => 'member',
                        'membership_start_date' => now()->toDateString(),
                        'status'                => 'active',
                    ]
                );

                GroupLink::firstOrCreate(
                    ['group_id' => $group->id, 'user_id' => $user->id],
                    [
                        'church_id' => $churchId,
                        'role'      => $groupRole,
                    ]
                );

                $userCount++;
            }

            $groupCount++;
            $this->command->info("  ✓ {$g['name']} (" . count($g['members']) . " members)");
        }

        $this->command->info("{$groupCount} groups, {$userCount} users seeded.");
    }
}
