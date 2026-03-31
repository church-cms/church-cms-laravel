<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\Common;

/**
 * Sermon Model
 *
 * Represents sermons/messages created and shared within a church.
 * Manages sermon content, voting system, related media links, and user engagement metrics.
 * Includes voting functionality to track likes/unlikes and custom vote accessors for quick retrieval.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int $church_id Foreign key to church
 * @property int $user_id Foreign key to sermon creator
 * @property string $title Sermon title/topic
 * @property string|null $description Sermon content/notes
 * @property string|null $cover_image Sermon cover/thumbnail image path
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Appended Attributes:
 * @property int $sermonlikevote Count of likes from vote table
 * @property int $sermonunlikevote Count of unlikes from vote table
 * @property-read string $cover_image_path Full path to cover image
 *
 * Relations:
 * @property-read \App\Models\Church $church The church this sermon belongs to
 * @property-read \App\Models\User $user The user/pastor who created this sermon
 * @property-read \Illuminate\Database\Eloquent\Collection $likes Users who marked as favorite (many-to-many)
 * @property-read \Illuminate\Database\Eloquent\Collection $sermonlinks Media links associated with sermon (audio, video, etc.)
 * @property-read \Illuminate\Database\Eloquent\Collection $vote All votes/ratings for this sermon
 *
 * Scopes:
 * @method static \Illuminate\Database\Eloquent\Builder byName(string $name) Filter by sermon title
 * @method static \Illuminate\Database\Eloquent\Builder byId(int $user_id) Filter by creator user ID
 *
 * Vote Accessors:
 * - sermonlikevote: Total count of users who liked this sermon
 * - sermonunlikevote: Total count of users who unliked this sermon
 * - userlikevote: Current authenticated user's like vote (if any)
 * - likevote: Computed state (1=liked, 0=not liked, 2=not voted)
 * - userunlikevote: Current authenticated user's unlike vote (if any)
 * - unlikevote: Computed state (1=unliked, 0=not unliked, 2=not voted)
 */
class Sermon extends Model
{
    //
    use SoftDeletes;
    use Common;

    /**
      * The table associated with the model.
      *
      * @var string
      */
    protected $table = 'sermons';

    /**
      * The attributes that are mass assignable.
      *
      * @var array
     */
    protected $fillable = [
      'church_id' , 'user_id' , 'title' , 'description' , 'cover_image'
    ];

    protected $with=['vote'];

    protected $appends=['sermonlikevote'];

    public function church()
    {
        return $this->belongsTo('App\Models\Church','church_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function likes()
    {
        return $this->belongsToMany('App\Models\User', 'likes');
    }

    public function sermonlinks()
    {
        return $this->hasMany('App\Models\SermonLink','sermons_id','id');
    }

    public function vote()
    {
        return $this->hasMany('App\Models\Vote', 'entity_id','id')->where('entity_name','=','App\\Models\\Sermon');
    }

    public function scopeByName($query , $name)
    {
        $query->where(function ($query) use($name)
        {
            $query->where('name','LIKE',$name.'%');
        });

        return $query;
    }

    public function scopeById($query , $user_id)
    {
        $query->where(function ($query) use($user_id)
        {
            $query->where('user_id',$user_id);
        });

        return $query;
    }

    public function getsermonlikevoteAttribute()
    {
        return $this->vote->where('like','1')->count();
    }

    public function getsermonunlikevoteAttribute()
    {
        return $this->vote->where('unlike','1')->count();
    }

    /*public function favorite()
    {
        return $this->hasMany('App\Models\Favorite', 'entity_id','id')->where('entity_name','=','App\\Models\\Sermon');
    }*/

    public function getuserlikevoteAttribute()
    {
        return $this->hasMany('App\Models\Vote', 'entity_id','id')->where('entity_name','=','App\\Models\\Sermon')->where('user_id',Auth::id())->orderBy('id','desc')->first();
    }

    public function getlikevoteAttribute()
    {
        $sermon = $this->userlikevote;
        if(count($sermon) > '0')
        {
            if($sermon->like == '1')
            {
                return 1;//liked
            }
            else
            {
                return 0;//not liked
            }
        }
        else
        {
            return 2;//not yet posted
        }
    }

    public function getuserunlikevoteAttribute()
    {
        return $this->hasMany('App\Models\Vote', 'entity_id','id')->where('entity_name','=','App\\Models\\Sermon')->where('user_id',Auth::id())->orderBy('id','desc')->first();
    }

    public function getunlikevoteAttribute()
    {
        $sermon = $this->userunlikevote;
        if(count($sermon) > '0')
        {
            if($sermon->unlike == '1')
            {
                return 1;//unliked
            }
            else
            {
                return 0;//not unliked
            }
        }
        else
        {
            return 2;//not yet posted
        }
    }

    public function getCoverImagePathAttribute()
    {
        return $this->getFilePath($this->cover_image);
    }

    public function getAudioCountAttribute()
    {
        return $this->sermonlinks->where('type','audio')->count();
    }

    public function getVideoCountAttribute()
    {
        return $this->sermonlinks->where('type','video')->count();
    }

    public function getFileCountAttribute()
    {
        return $this->sermonlinks->where('type','document')->count();
    }
}
