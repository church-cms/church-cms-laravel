<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id' , 'user_id' , 'entity_id' , 'entity_name' , 'title' , 'category' , 'date' , 'is_present' , 'present_at','type','staff_id','meeting_type','description','meeting_maincat_id','meeting_subcat_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    // protected $casts = [
//     'expired_at' => 'datetime'
// ];
    protected $dates = [ 'date'  , 'deleted_at' ];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
   	{
   		return $this->belongsTo('App\Models\User','user_id');
   	}

    public function userprofile()
    {
      return $this->belongsTo('App\Models\Userprofile','user_id','user_id');
    }

    public function staffuser()
    {
      return $this->belongsTo('App\Models\User','staff_id');
    }

   	public function events()
   	{
   		return $this->belongsTo('App\Models\Events','entity_id');
   	}

    public function maincat()
    {
      return $this->belongsTo('App\Models\MeetingCategory','meeting_maincat_id');
    }
     public function subcat()
    {
      return $this->belongsTo('App\Models\MeetingCategory','meeting_subcat_id');
    }
}