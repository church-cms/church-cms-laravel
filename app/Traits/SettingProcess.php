<?php
namespace App\Traits;

use App\Models\ChurchDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Trait SettingProcess
 *
 * Manages application settings and configuration including:
 * - Updating setting values by key
 * - Persisting configuration changes to database
 * - Retrieving and modifying application settings
 *
 * @package App\Traits
 */
trait SettingProcess
{
    /**
     * Update an application setting.
     *
     * Updates the value of a setting identified by its key and persists
     * the change to the database.
     *
     * @param string $key The setting key/identifier
     * @param string $value The new setting value
     *
     * @return \App\Models\Setting|null The updated setting model, or null on failure
     */
    public function updatesettings(string $key, string $value): ?object {
        try {
            $churchId = Auth::user()?->church_id;

            if ($churchId) {
                return ChurchDetail::updateOrCreate(
                    ['church_id' => $churchId, 'meta_key' => $key],
                    ['meta_value' => $value]
                );
            }

            $setting = ChurchDetail::where('meta_key', $key)->first();

            if ($setting) {
                $setting->meta_value = $value;
                $setting->save();

                return $setting;
            }

            return null;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }
}
