<?php

use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * FamilyTestSeeder
 *
 * Seeds 10 family members linked to the first church-member user (usergroup_id = 5).
 * Each member is a real User row with ref_id pointing to the head, plus a Userprofile.
 *
 * Run: php artisan db:seed --class=FamilyTestSeeder
 * Safe to re-run – uses firstOrCreate on email / user_id.
 */
class FamilyTestSeeder extends Seeder
{
    public function run()
    {
        // ── Find the head user ──────────────────────────────────────────────
        $head = User::where('usergroup_id', 5)->first()
             ?? User::first();

        if (! $head) {
            $this->command->error('No user found. Run InstallerSeeder / church:setup first.');
            return;
        }

        $churchId = $head->church_id;
        $lastName = optional($head->userprofile)->lastname ?? 'Smith';

        // ── Member definitions ──────────────────────────────────────────────
        // [relation, family, firstname, gender, date_of_birth, marriage_status, profession, was_baptized]
        $members = [
            ['partner', 'partner', 'Sarah',   'female', '1988-03-14', 'married',         'teacher',           'yes'],
            ['child',   'child',   'Ethan',   'male',   '2012-06-22', 'single',           'student',           'no'],
            ['child',   'child',   'Lily',    'female', '2014-11-05', 'single',           'student',           'no'],
            ['child',   'child',   'Noah',    'male',   '2017-02-19', 'single',           'student',           'no'],
            ['child',   'child',   'Emma',    'female', '2019-08-30', 'single',           'student',           'no'],
            ['father',  'father',  'Robert',  'male',   '1955-09-01', 'married',          'pastor',            'yes'],
            ['mother',  'mother',  'Grace',   'female', '1960-12-25', 'married',          'home_maker',        'yes'],
            ['child',   'child',   'Lucas',   'male',   '2010-04-07', 'single',           'student',           'yes'],
            ['sibling', 'sibling', 'David',   'male',   '1990-04-18', 'married',          'business',          'yes'],
            ['sibling', 'sibling', 'Ruth',    'female', '1993-01-11', 'single',           'teacher',           'yes'],
        ];

        $count = 0;

        foreach ($members as $i => [$relation, $family, $firstname, $gender, $dob, $marital, $profession, $baptized]) {
            $email = strtolower($firstname . '.' . $i . '@family.test');

            // Create user (idempotent)
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'         => "{$firstname} {$lastName}",
                    'password'     => Hash::make('password'),
                    'mobile_no'    => '0000000' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'church_id'    => $churchId,
                    'usergroup_id' => 5,          // church member
                    'ref_id'       => $head->id,  // links to head of household
                    'email_verified' => 1,
                ]
            );

            // Create profile (idempotent)
            Userprofile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'church_id'             => $churchId,
                    'firstname'             => $firstname,
                    'lastname'              => $lastName,
                    'gender'                => $gender,
                    'date_of_birth'         => $dob,
                    'relation'              => $relation,
                    'family'                => $family,
                    'profession'            => $profession,
                    'marriage_status'       => $marital,
                    'was_baptized'          => $baptized,
                    'baptism_date'          => $baptized === 'yes' ? '2010-01-01' : null,
                    'membership_type'       => 'member',
                    'membership_start_date' => now()->toDateString(),
                    'status'                => 'active',
                ]
            );

            $count++;
        }

        $this->command->info(
            "{$count} family members seeded under user #{$head->id} ({$head->email})."
        );
    }
}
