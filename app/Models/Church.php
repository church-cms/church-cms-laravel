<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Church Model
 *
 * Represents the main church entity in the Church CMS application.
 * Central model managing church details, members, events, and all church-related operations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string $name Church name
 * @property string|null $quotes Church motto or quotes
 * @property string|null $address Physical address
 * @property int|null $state_id State foreign key
 * @property int|null $city_id City foreign key
 * @property string|null $pincode ZIP/Postal code
 * @property string|null $slug URL-friendly identifier
 * @property int $status Church status (active/inactive)
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\State $state The state where church is located
 * @property-read \App\Models\City $city The city where church is located
 * @property-read \Illuminate\Database\Eloquent\Collection $user Church member users
 * @property-read \Illuminate\Database\Eloquent\Collection $admin Church administrators
 * @property-read \Illuminate\Database\Eloquent\Collection $userprofile Member profiles
 * @property-read \Illuminate\Database\Eloquent\Collection $event Church events
 * @property-read \Illuminate\Database\Eloquent\Collection $subscription Church subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection $reminder Church reminders
 * @property-read \Illuminate\Database\Eloquent\Collection $sermonlink Sermon links
 * @property-read \Illuminate\Database\Eloquent\Collection $bulletin Church bulletins
 * @property-read \Illuminate\Database\Eloquent\Collection $group Church groups
 * @property-read \Illuminate\Database\Eloquent\Collection $groupLink Member group links
 * @property-read \Illuminate\Database\Eloquent\Collection $sendMail Church emails
 * @property-read \Illuminate\Database\Eloquent\Collection $prayerRequest Prayer requests
 * @property-read \Illuminate\Database\Eloquent\Collection $fund Church funds
 * @property-read \Illuminate\Database\Eloquent\Collection $newsLetter Newsletters
 * @property-read \Illuminate\Database\Eloquent\Collection $faq Frequently asked questions
 */
class Church extends Model
{    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'church';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'quotes' , 'address' , 'state_id' , 'city_id' , 'pincode' , 'slug' , 'status'
    ];

    public function state()
    {
        return $this->hasOne('App\Models\State','id','state_id');
    }

    public function city()
    {
        return $this->hasOne('App\Models\City','id','city_id');
    }

    public function user()
    {
    	return $this->hasMany('App\Models\User','church_id','id')->where('usergroup_id',5);
    }

    public function admin()
    {
        return $this->hasMany('App\Models\User','church_id','id')->where('usergroup_id',3);
    }

    public function userprofile()
    {
        return $this->hasMany('App\Models\Userprofile','church_id','id');
    }

    public function event()
    {
        return $this->hasMany('App\Models\Event','church_id','id');
    }

    public function subscription()
    {
        return $this->hasMany('App\Models\Subscription','church_id','id');
    }

     public function reminder()
    {
        return $this->hasMany('App\Models\Reminder','church_id','id');
    }

    public function fulladdress()
    {
        $fulladdress =  $this->address
                        . "<br/>". $this->city->name
                        . "<br />" .$this->pincode
                        . "<br /> ". $this->state->name
                        . "<br /> ". "India" ;

        return $fulladdress;
    }

    public function sermonlink()
    {
        return $this->hasMany('App\Models\SermonLink','church_id','id');
    }

    public function bulletin()
    {
        return $this->hasMany('App\Models\Bulletin','church_id','id');
    }

    public function group()
    {
        return $this->hasMany('App\Models\Group','church_id','id');
    }

    public function groupLink()
    {
        return $this->hasMany('App\Models\GroupLink','church_id','id');
    }

    public function sendMail()
    {
        return $this->hasMany('App\Models\SendMail','church_id','id');
    }

    public function scopeIsActive($query,$church_id)
    {
        $query->where(function ($query) use($church_id)
            {
                $query  ->where('id',$church_id)
                        ->where('status',1);
            });
        return $query;
    }

    public function getActiveMembersAttribute()
    {
        return $this->hasMany('App\Models\User','church_id','id')->with('user')->where('usergroup_id',5)->whereHas('userprofile', function ($query)
            {
                $query->where('status','active');
            })->count();
    }

    public function getPaidMembersAttribute()
    {
        return $this->userprofile->where('membership_type','member')->count();
    }

   public function prayerRequest()
    {
        return $this->hasMany('App\Models\PrayerRequest','church_id','id');
    }

    public function prayerResponse()
    {
        return $this->hasMany('App\Models\PrayerResponse','church_id','id');
    }

    public function help()
    {
        return $this->hasMany('App\Models\Help','church_id','id');
    }

    public function attendance()
    {
        return $this->hasMany('App\Models\Attendance','church_id','id');
    }

    public function videoConference()
    {
        return $this->hasMany('App\Models\VideoConference','church_id','id');
    }

    public function churchDetail()
    {
        return $this->hasOne('App\Models\ChurchDetail','church_id','id');
    }

    public function churchDetailLogo()
    {
        return $this->hasOne('App\Models\ChurchDetail','church_id','id')->where('meta_key','church_logo');
    }

    public function eventGallery()
    {
        return $this->hasMany('App\Models\EventGallery','church_id','id');
    }

    public function favorite()
    {
        return $this->hasMany('App\Models\Favorite','church_id','id');
    }

    public function fund()
    {
        return $this->hasMany('App\Models\Fund','church_id','id');
    }

    public function contact()
    {
        return $this->hasMany('App\Models\Fund','church_id','id');
    }

    public function newsLetter()
    {
        return $this->hasMany('App\Models\NewsLetter','church_id','id');
    }

    public function faq()
    {
        return $this->hasMany('App\Models\Faq','church_id','id');
    }

    public function faqCategory()
    {
        return $this->hasMany('App\Models\FaqCategory','church_id','id');
    }
      public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
