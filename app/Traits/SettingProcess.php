<?php
namespace App\Traits;

use App\Models\ChurchDetail;
use Exception;
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
            $setting = ChurchDetail::where('meta_key', $key)->first();
            $setting->meta_value = $value;
            $setting->save();

            return $setting;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }
}
