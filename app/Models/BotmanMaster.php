<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BotmanTag;

/**
 * BotmanMaster Model
 *
 * Represents master configurations for the Botman chatbot system.
 * Manages chatbot answers, keywords, and bot behavior settings.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $answers Chatbot answer responses
 * @property string|null $status Status of the configuration
 * @property int|null $created_by User who created this configuration
 * @property int|null $updated_by User who last updated this configuration
 * @property \Carbon\Carbon|null $deleted_at Soft delete timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \Illuminate\Database\Eloquent\Collection $tags Associated tags for this master
 * @property-read \App\Models\User $userInfo Creator user information
 */
class BotmanMaster extends Model
{
    use SoftDeletes;

      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'botman_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answers', 'status', 'created_by', 'updated_by'
    ];

    public function tags(){
        return $this->hasMany('App\Models\BotmanTag', 'master_id', 'id');
    }

    public function userInfo(){
        return $this->hasOne('App\Models\User','id','created_by');
    }
}
