<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Core seed data required for every installation.
     *
     * Run automatically by: php artisan migrate:fresh --seed
     * For demo/development data run: php artisan db:seed --class=DemoDataSeeder
     */
    public function run()
    {
        $this->call([
            UsergroupTableSeeder::class,
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            PermissionTableSeeder::class,
            RoleSeeder::class,
            GroupCategoryTableSeeder::class,
            MailTemplatesSeeder::class,
            SmsTemplatesTableSeeder::class,
            KeywordsTableSeeder::class,
            SettingsTableSeeder::class,
            PaymentgatewaysTableSeeder::class,
            BibleTableSeeder::class,
        ]);
    }
}
