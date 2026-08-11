<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Mailtemplate Model
 *
 * Represents email message templates.
 * Stores pre-formatted email templates for campaigns and notifications.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property string|null $name Template name
 * @property string|null $subject Email subject template
 * @property string|null $mail_content Email body HTML template
 * @property int $status Template status (active/inactive)
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 */
class Mailtemplate extends Model
{
    //

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='mailtemplates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
    	'name' , 'subject' , 'mail_content' , 'status'
    ];
}
