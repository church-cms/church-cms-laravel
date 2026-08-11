<?php

/**
 * Trait for processing common
 */
namespace App\Traits;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Log;

/**
 * Trait for user filtering and retrieval operations
 *
 * Provides functionality for:
 * - Filtering church members with various search criteria
 * - Filtering administrators and staff
 * - Filtering preachers and guest users
 * - Supporting advanced search with multiple filters (name, gender, age, etc)
 *
 * @package App\Traits
 */
trait MemberProcess
{
    /**
     * Get filtered member users for a church.
     *
     * Filters church members with optional search parameters including names,
     * gender, membership type, baptism status, profession, contact info, and location.
     *
     * @param \Illuminate\Http\Request $request The request object containing filter parameters
     * @param int $church_id The church ID to filter members for
     * @param int $usergroup_id The user group ID to filter by
     *
     * @return \Illuminate\Http\Resources\AnonymousResourceCollection|null The filtered collection of users
     */
    public function MemberFilter(Request $request, int $church_id, int $usergroup_id): ?object {
        try {
            $users = User::with(['userprofile.state', 'userprofile.city', 'usergroup'])
                ->ByChurch($church_id)->ByRole($usergroup_id)->whereHas('userprofile', function ($q) {
                    $q->where('membership_type', 'member')->orWhere('membership_type', null);
                });

            $alphabet = $request->alphabet ?? '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }

            if (count(array(\Request::getQueryString())) > 0) {
                $firstname = $request->firstname ?? '';
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname ?? '';
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $gender = $request->gender ?? '';
                if ($gender != '') {
                    $users = $users->ByGender($gender);
                }

                $membership_type = $request->membership_type ?? '';
                if ($membership_type != '') {
                    $users = $users->ByMembershipType($membership_type);
                }

                $marriage_status = $request->marriage_status ?? '';
                if ($marriage_status != '') {
                    $users = $users->ByMarriageStatus($marriage_status);
                }

                $min_search = $request->min_age ?? '';
                $max_search = $request->max_age ?? '';
                if (($min_search != '') && ($max_search != '')) {
                    $min_age = date('Y') - $min_search;
                    $max_age = date('Y') - $max_search;
                    $users = $users->ByAge($min_age, $max_age);
                }

                $date_of_birth = $request->date_of_birth ?? '';
                if ($date_of_birth != '1970-01-01' && $date_of_birth != '') {
                    $users = $users->ByDateOfBirth($date_of_birth);
                }

                $baptism = $request->baptism ?? '';
                if ($baptism != '') {
                    $users = $users->ByBaptism($baptism);
                }

                $family = $request->family ?? '';
                if ($family != '') {
                    $users = $users->ByFamily($family);
                }

                $profession = $request->profession ?? '';
                if ($profession != '') {
                    $users = $users->ByProfession($profession);
                }

                $mobile_no = $request->mobile_no ?? '';
                if ($mobile_no != '') {
                    $users = $users->ByMobile_no($mobile_no);
                }

                $email = $request->email ?? '';
                if ($email != '') {
                    $users = $users->ByEmail_id($email);
                }

                $location = $request->location ?? '';
                if ($location != '') {
                    $users = $users->ByLocation($location);
                }
            }

            $users = $users->get();
            return UserResource::collection($users);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * Get filtered administrator users.
     *
     * Filters administrators and staff with optional search parameters.
     *
     * @param \Illuminate\Http\Request $request The request object containing filter parameters
     * @param int $church_id The church ID to filter administrators for
     * @param int $usergroup_id The user group ID to filter by
     *
     * @return \Illuminate\Http\Resources\AnonymousResourceCollection|null The filtered collection of users
     */
    public function SubAdminFilter(Request $request, int $church_id, int $usergroup_id): ?object {
        try {
            $users = User::ByChurch($church_id)->ByRole($usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            $alphabet = $request->alphabet ?? '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }

            if (count(array(\Request::getQueryString())) > 0) {
                $firstname = $request->firstname ?? '';
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname ?? '';
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $gender = $request->gender ?? '';
                if ($gender != '') {
                    $users = $users->ByGender($gender);
                }

                $min_search = $request->min_age ?? '';
                $max_search = $request->max_age ?? '';
                if (($min_search != '') && ($max_search != '')) {
                    $min_age = date('Y') - $min_search;
                    $max_age = date('Y') - $max_search;
                    $users = $users->ByAge($min_age, $max_age);
                }

                $date_of_birth = $request->date_of_birth ?? '';
                if ($date_of_birth != '1970-01-01' && $date_of_birth != '') {
                    $users = $users->ByDateOfBirth($date_of_birth);
                }

                $profession = $request->profession ?? '';
                if ($profession != '') {
                    $users = $users->ByProfession($profession);
                }

                $mobile_no = $request->mobile_no ?? '';
                if ($mobile_no != '') {
                    $users = $users->ByMobile_no($mobile_no);
                }

                $email = $request->email ?? '';
                if ($email != '') {
                    $users = $users->ByEmail_id($email);
                }

                $location = $request->location ?? '';
                if ($location != '') {
                    $users = $users->ByLocation($location);
                }
            }

            $users = $users->get();
            return UserResource::collection($users);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * Get filtered preacher users.
     *
     * Filters preachers and ministry staff with optional search parameters.
     *
     * @param \Illuminate\Http\Request $request The request object containing filter parameters
     * @param int $church_id The church ID to filter preachers for
     * @param int $usergroup_id The user group ID to filter by
     *
     * @return \Illuminate\Http\Resources\AnonymousResourceCollection|null The filtered collection of users
     */
    public function PreacherFilter(Request $request, int $church_id, int $usergroup_id): ?object {
        try {
            $users = User::ByChurch($church_id)->ByRole($usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            $alphabet = $request->alphabet ?? '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }

            if (count(array(\Request::getQueryString())) > 0) {
                $firstname = $request->firstname ?? '';
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname ?? '';
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $mobile_no = $request->mobile_no ?? '';
                if ($mobile_no != '') {
                    $users = $users->ByMobile_no($mobile_no);
                }

                $email = $request->email ?? '';
                if ($email != '') {
                    $users = $users->ByEmail_id($email);
                }

                $location = $request->location ?? '';
                if ($location != '') {
                    $users = $users->ByLocation($location);
                }
            }

            $users = $users->get();
            return UserResource::collection($users);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * Get filtered guest users.
     *
     * Filters guest users with optional search parameters.
     *
     * @param \Illuminate\Http\Request $request The request object containing filter parameters
     * @param int $church_id The church ID to filter guests for
     * @param int $usergroup_id The user group ID to filter by
     *
     * @return \Illuminate\Http\Resources\AnonymousResourceCollection|null The filtered collection of users
     */
    public function GuestFilter(Request $request, int $church_id, int $usergroup_id): ?object {
        try {
            $users = User::ByChurch($church_id)->ByRole($usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where([['status', 'active'], ['membership_type', 'guest']])
                  ->orWhere([['status', 'inactive'], ['membership_type', 'guest']]);
            });

            $alphabet = $request->alphabet ?? '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }

            if (count(array(\Request::getQueryString())) > 0) {
                $firstname = $request->firstname ?? '';
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname ?? '';
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $gender = $request->gender ?? '';
                if ($gender != '') {
                    $users = $users->ByGender($gender);
                }

                $min_search = $request->min_age ?? '';
                $max_search = $request->max_age ?? '';
                if (($min_search != '') && ($max_search != '')) {
                    $min_age = date('Y') - $min_search;
                    $max_age = date('Y') - $max_search;
                    $users = $users->ByAge($min_age, $max_age);
                }

                $date_of_birth = $request->date_of_birth ?? '';
                if ($date_of_birth != '1970-01-01' && $date_of_birth != '') {
                    $users = $users->ByDateOfBirth($date_of_birth);
                }

                $profession = $request->profession ?? '';
                if ($profession != '') {
                    $users = $users->ByProfession($profession);
                }

                $mobile_no = $request->mobile_no ?? '';
                if ($mobile_no != '') {
                    $users = $users->ByMobile_no($mobile_no);
                }

                $email = $request->email ?? '';
                if ($email != '') {
                    $users = $users->ByEmail_id($email);
                }

                $location = $request->location ?? '';
                if ($location != '') {
                    $users = $users->ByLocation($location);
                }
            }

            $users = $users->get();
            return UserResource::collection($users);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }
}
