<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstallerSeeder extends Seeder
{
    /**
     * Run the database seeds for a fresh installation.
     * Seeds system-wide data (countries, permissions, settings) but NOT multi-church fixtures
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks during seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Seed user groups/roles via laratrust seeder
            $this->callSilent(UsergroupTableSeeder::class);

            // Seed permission structure
            $this->callSilent(PermissionTableSeeder::class);

            // Seed geographic reference data
            $this->callSilent(CountriesTableSeeder::class);
            $this->callSilent(StatesTableSeeder::class);
            $this->callSilent(CitiesTableSeeder::class);

            // Seed system settings and templates
            $this->callSilent(SettingsTableSeeder::class);
            $this->callSilent(MailTemplatesSeeder::class);
            $this->callSilent(SmsTemplatesTableSeeder::class);

            // Seed Bible data
            $this->callSilent(BibleTableSeeder::class);
            $this->callSilent(BibleVerseTableSeeder::class);

            // Seed plans/subscription tiers
            $this->callSilent(PlansTableSeeder::class);

            // NOTE: Do NOT seed:
            // - Users/admin users (created via church:install-data command)
            // - Churches (created via church:install-data command)
            // - Events, Members, Gallery, or other church-specific data
            // The installer will create exactly one church and one admin
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}

