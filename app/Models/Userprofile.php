<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;

/**
 * Userprofile Model
 *
 * Extended user profile containing demographic, personal, and membership information.
 * Extends the User model with church-specific profile data including family relationships,
 * geographic location, membership status, and personal identifiers. Uses PresentableTrait
 * for view-layer formatting and supports filtering by church, role, and demographics.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int $user_id Foreign key to user (one-to-one relationship)
 * @property string|null $firstname User first name
 * @property string|null $lastname User last name
 * @property string|null $birth_firstname Birth first name (for records)
 * @property string|null $birth_lastname Birth last name (maiden name, etc.)
 * @property string|null $gender Gender (M/F/Other)
 * @property \Carbon\Carbon|null $date_of_birth User's date of birth
 * @property bool|null $was_baptized Baptism status indicator
 * @property \Carbon\Carbon|null $baptism_date Date of baptism
 * @property string|null $profession User's occupation/profession
 * @property string|null $address Street address
 * @property int|null $city_id Foreign key to city (geographic)
 * @property int|null $state_id Foreign key to state (geographic)
 * @property int|null $country_id Foreign key to country (geographic)
 * @property string|null $pincode Postal/ZIP code
 * @property string|null $membership_type Type of membership (member, guest, etc.)
 * @property \Carbon\Carbon|null $membership_start_date When user became member
 * @property array|null $membership_end_date When membership ends (null if active)
 * @property string|null $family Family relationship to reference user (child, patner, father, mother)
 * @property string|null $marriage_status Marriage status (single, married, widowed, divorced)
 * @property \Carbon\Carbon|null $marriage_start_date Marriage/partnership date
 * @property string|null $notes Internal notes about member
 * @property string|null $avatar Profile picture/avatar path
 * @property string|null $status Member status (active, inactive, removed)
 * @property string|null $aadhar_number National ID number (Aadhar)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this profile belongs to
 * @property-read \App\Models\User $user The associated user account
 * @property-read \App\Models\User $members User who referred this user (family hierarchy)
 * @property-read \App\Models\Country $country User's country
 * @property-read \App\Models\State $state User's state/province
 * @property-read \App\Models\City $city User's city
 *
 * Scopes:
 * @method static \Illuminate\Database\Eloquent\Builder byChurch(int $church_id) Filter by specific church
 * @method static \Illuminate\Database\Eloquent\Builder byRole(int $usergroup_id) Filter by user role/group
 * @method static \Illuminate\Database\Eloquent\Builder byAverageAge() Filter by average age calculation
 * @method static \Illuminate\Database\Eloquent\Builder byGender(string $gender) Filter by gender
 * @method static \Illuminate\Database\Eloquent\Builder byMembershipType(string $type) Filter by membership type
 * @method static \Illuminate\Database\Eloquent\Builder byStatus(string $status) Filter by member status
 *
 * Dates Cast:
 * - date_of_birth: Carbon instance
 * - baptism_date: Carbon instance
 * - membership_start_date: Carbon instance
 * - marriage_start_date: Carbon instance (via dates array)
 * - deleted_at: Carbon instance (soft delete)
 */
class Userprofile extends Model
{
    use PresentableTrait;
    use SoftDeletes;
    use Common;

    protected $presenter = "App\Presenters\UserprofilePresenter";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userprofiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'firstname' , 'lastname' , 'birth_firstname' , 'birth_lastname' , 'gender' , 'date_of_birth' , 'was_baptized' , 'baptism_date' , 'profession' , 'address' , 'city_id' , 'state_id' , 'country_id' , 'pincode' , 'membership_type' , 'membership_start_date' , 'membership_end_date' , 'family' , 'marriage_status' , 'marriage_start_date' , 'notes' , 'avatar' , 'status' , 'aadhar_number'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_of_birth','baptism_date','membership_start_date','marriage_start_date','deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=['membership_end_date'=>'array'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function members()
    {
      return $this->belongsTo('App\Models\User','ref_id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }

    public function state()
    {
        return $this->hasOne('App\Models\State','id','state_id');
    }

    public function city()
    {
        return $this->hasOne('App\Models\City','id','city_id');
    }

    public function scopeByChurch($query,$church_id)
    {
        $query->where('church_id',$church_id);

        return $query;
    }

    public function scopeByRole($query,$usergroup_id)
    {
        $query->wherehas('user',function ($query) use($usergroup_id)
        {
            $query->where('usergroup_id',$usergroup_id);
        });
        return $query;
    }

    public function scopeByAverageAge($query)
    {
        $query->where('usergroup_id',$usergroup_id);
        return $query;
    }

    public function scopeByGender($query , $gender)
    {
        $query->where('gender',$gender);

        return $query;
    }

    public function scopeByMembershipType($query , $membership_type)
    {
        $query->where('membership_type',$membership_type);

        return $query;
    }

    public function scopeByStatus($query , $status)
    {
        $query->where('status',$status);

        return $query;
    }

    public function scopeByBaptism($query , $baptism)
    {
        $query->where('was_baptized','LIKE','%'.$baptism.'%');

        return $query;
    }

    public function getAvatarPathAttribute()
    {
        return $this->getFilePath($this->avatar);
    }
}
