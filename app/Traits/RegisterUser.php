<?php

/**
 * Trait for processing RegisterUser
 */

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Userprofile;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * Trait for user registration processes
 *
 * Provides functionality for:
 * - Creating regular user accounts with profiles
 * - Creating guest user accounts
 * - Creating subscriber accounts
 * - Handling user profile data and avatar selection
 * - Database transaction management
 *
 * @package App\Traits
 */
trait RegisterUser
{
    /**
     * Create a new user account with full profile.
     *
     * Creates a user record and associated profile with all provided data.
     * Handles referral relationships, membership types, and avatar assignment.
     * Uses database transactions for data integrity.
     *
     * @param object $data User data object containing all user fields
     * @param int $church_id The church ID for the user
     * @param string $path The avatar file path (optional)
     * @param int $usergroup_id The user group/role ID
     *
     * @return \App\Models\User|null The created user object, or null on failure
     */
    public function CreateUser(object $data, int $church_id, string $path = '', int $usergroup_id): ?User
    {
        \DB::beginTransaction();
        try {
            $ref_user = null;
            if (!is_null($data->ref_name)) {
                $ref_user = User::where('name', $data->ref_name)->first();
            }

            $user = new User;
            $user->church_id = $church_id;
            $user->usergroup_id = $usergroup_id;

            if (!is_null($data->ref_name) && $ref_user) {
                $user->ref_id = $ref_user->id;
            }

            if (!is_null($data->name)) {
                $user->name = $data->name;
            }

            $user->password = bcrypt(config('app.default_password', 'password'));
            $user->email = $data->email;
            $user->mobile_no = $data->mobile_no;
            $user->email_verification_code = Str::random(40);

            $user->save();

            $userprofile = new Userprofile;

            if (!is_null($data->ref_name)) {
                $userprofile->relation = $data->relation;
            }

            $userprofile->church_id = $church_id;
            $userprofile->user_id = $user->id;
            $userprofile->firstname = $data->firstname;

            if (!is_null($data->lastname)) {
                $userprofile->lastname = $data->lastname;
            }
            if (!is_null($data->birth_firstname)) {
                $userprofile->birth_firstname = $data->birth_firstname;
            }
            if (!is_null($data->birth_lastname)) {
                $userprofile->birth_lastname = $data->birth_lastname;
            }
            if (!is_null($data->gender)) {
                $userprofile->gender = $data->gender;
            }
            if (!is_null($data->date_of_birth)) {
                $userprofile->date_of_birth = $data->date_of_birth;
            }
            if (!is_null($data->was_baptized)) {
                $userprofile->was_baptized = $data->was_baptized;
            }
            if (!is_null($data->baptism_date)) {
                $userprofile->baptism_date = $data->baptism_date;
            }
            if (!is_null($data->profession)) {
                $userprofile->profession = $data->profession;
            }
            if (!is_null($data->sub_occupation)) {
                $userprofile->sub_occupation = $data->sub_occupation;
            }
            if (!is_null($data->address)) {
                $userprofile->address = $data->address;
            }
            if (!is_null($data->city_id)) {
                $userprofile->city_id = $data->city_id;
            }
            if (!is_null($data->state_id)) {
                $userprofile->state_id = $data->state_id;
            }
            if (!is_null($data->country_id)) {
                $userprofile->country_id = $data->country_id;
            }
            if (!is_null($data->pincode)) {
                $userprofile->pincode = $data->pincode;
            }

            if (!is_null($data->membership_type)) {
                $userprofile->membership_type = $data->membership_type;
                if ($userprofile->membership_type == "member") {
                    $userprofile->membership_start_date = Carbon::now();
                }
            }

            if (!is_null($data->family)) {
                $userprofile->family = $data->family;
            }
            if (!is_null($data->marriage_status)) {
                $userprofile->marriage_status = $data->marriage_status;
            }
            if (!is_null($data->marriage_start_date)) {
                $userprofile->marriage_start_date = $data->marriage_start_date;
            }
            if (!is_null($data->marriage_end_date)) {
                $userprofile->marriage_end_date = $data->marriage_end_date;
            }
            if (!is_null($data->aadhar_number)) {
                $userprofile->aadhar_number = $data->aadhar_number;
            }
            if (!is_null($data->notes)) {
                $userprofile->notes = $data->notes;
            }

            if ($path != '') {
                $userprofile->avatar = $path;
            } else {
                if ($userprofile->gender == 'male') {
                    $userprofile->avatar = "uploads/male.png";
                } elseif ($userprofile->gender == 'female') {
                    $userprofile->avatar = "uploads/female.png";
                } else {
                    $userprofile->avatar = "uploads/images.jpg";
                }
            }

            if (!is_null($data->facebook_id)) {
                $userprofile->facebook_id = $data->facebook_id;
            }
            if (!is_null($data->description)) {
                $userprofile->description = $data->description;
            }

            $userprofile->save();

            \DB::commit();
            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * Create a guest user account.
     *
     * Creates a simplified user and profile with guest membership status.
     * Useful for one-time visitors or temporary attendees.
     *
     * @param object $data Guest user data object
     * @param int $church_id The church ID for the guest
     * @param string $path The avatar file path (optional)
     * @param int $usergroup_id The user group/role ID
     *
     * @return \App\Models\User|null The created user object, or null on failure
     */
    public function createGuest(object $data, int $church_id, string $path = '', int $usergroup_id): ?User
    {
        \DB::beginTransaction();
        try {
            $user = new User;

            $user->church_id = $church_id;
            $user->usergroup_id = $usergroup_id;

            if (!is_null($data->name)) {
                $user->name = $data->name;
            }

            $user->password = bcrypt(config('app.default_password', 'password'));
            $user->email = $data->email;
            $user->mobile_no = $data->mobile_no;
            $user->mobile_country_code = $data->mobile_country_code;
            $user->email_verification_code = Str::random(40);

            $user->save();

            $userprofile = new Userprofile;

            $userprofile->church_id = $church_id;
            $userprofile->user_id = $user->id;
            $userprofile->firstname = $data->firstname;

            if (!is_null($data->lastname)) {
                $userprofile->lastname = $data->lastname;
            }
            if (!is_null($data->gender)) {
                $userprofile->gender = $data->gender;
            }
            if (!is_null($data->date_of_birth)) {
                $userprofile->date_of_birth = $data->date_of_birth;
            }
            if (!is_null($data->was_baptized)) {
                $userprofile->was_baptized = $data->was_baptized;
            }
            if (!is_null($data->baptism_date)) {
                $userprofile->baptism_date = $data->baptism_date;
            }
            if (!is_null($data->profession)) {
                $userprofile->profession = $data->profession;
            }
            if (!is_null($data->sub_occupation)) {
                $userprofile->sub_occupation = $data->sub_occupation;
            }
            if (!is_null($data->address)) {
                $userprofile->address = $data->address;
            }
            if (!is_null($data->city_id)) {
                $userprofile->city_id = $data->city_id;
            }
            if (!is_null($data->state_id)) {
                $userprofile->state_id = $data->state_id;
            }
            if (!is_null($data->country_id)) {
                $userprofile->country_id = $data->country_id;
            }
            if (!is_null($data->pincode)) {
                $userprofile->pincode = $data->pincode;
            }

            $userprofile->membership_type = 'guest';

            if (!is_null($data->aadhar_number)) {
                $userprofile->aadhar_number = $data->aadhar_number;
            }
            if (!is_null($data->notes)) {
                $userprofile->notes = $data->notes;
            }

            if ($userprofile->gender == 'male') {
                $userprofile->avatar = "uploads/male.png";
            } elseif ($userprofile->gender == 'female') {
                $userprofile->avatar = "uploads/female.png";
            } else {
                $userprofile->avatar = "uploads/images.jpg";
            }

            $userprofile->save();

            \DB::commit();
            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * Create a new subscriber account.
     *
     * Creates a subscriber record for newsletter/mailing list subscriptions.
     *
     * @param object $data Subscriber data object containing firstname, lastname, email, etc
     *
     * @return \App\Models\Subscribers|null The created subscriber object, or null on failure
     */
    public function CreateSubscriber(object $data): ?object
    {
        try {
            $subscriber = new Subscribers;

            $subscriber->firstname  = $data->firstname;
            $subscriber->lastname   = $data->lastname ?? '';
            $subscriber->email      = $data->email;
            $subscriber->aff        = $data->aff ?? '';
            $subscriber->source     = $data->source ?? '';
            $subscriber->is_active  = $data->is_active ?? 1;

            $subscriber->save();

            return $subscriber;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }
}
