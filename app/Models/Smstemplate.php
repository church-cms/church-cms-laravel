<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Smstemplate Model
 *
 * Represents SMS message templates.
 * Stores pre-formatted SMS templates for campaigns and notifications.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Template name
 * @property string|null $content SMS message template content
 * @property int $status Template status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Smstemplate extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table    = 'sms_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'template' , 'content' , 'status'
    ];
}
