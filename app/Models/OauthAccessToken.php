<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * OauthAccessToken Model
 *
 * Manages OAuth 2.0 access tokens for API authentication.
 * Stores tokens for secure API access and third-party integrations.
 *
 * @package App\Models
 * @property int $id Primary key
 * @property int|null $user_id Foreign key to user
 * @property string $client_id OAuth client identifier
 * @property string $name Token name/label
 * @property array|null $scopes Authorized scopes as JSON
 * @property \Carbon\Carbon|null $revoked_at Revocation timestamp
 * @property \Carbon\Carbon|null $expires_at Token expiration timestamp
 * @property \Carbon\Carbon $created_at Record creation timestamp
 * @property \Carbon\Carbon $updated_at Record update timestamp
 *
 * Relations:
 * @property-read \App\Models\User|null $user The user this token belongs to
 */
class OauthAccessToken extends Model
{
   //
}
