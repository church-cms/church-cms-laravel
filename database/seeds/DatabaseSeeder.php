<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
    * Seed the application's database.
    *
    * @return void
    */

    public function run() {
        //default seeders//
        $this->call( UsergroupTableSeeder::class );
        $this->call( CountriesTableSeeder::class );
        $this->call( StatesTableSeeder::class );
        $this->call( CitiesTableSeeder::class );
        $this->call( PermissionTableSeeder::class );
        $this->call( GroupCategoryTableSeeder::class );
        $this->call( PlansTableSeeder::class );
        $this->call( MailTemplatesSeeder::class );
        $this->call( SmsTemplatesTableSeeder::class );
        $this->call( KeywordsTableSeeder::class );
        $this->call( SettingsTableSeeder::class );
        $this->call( PaymentgatewaysTableSeeder::class );
        // UsersTableSeeder removed - users created via installer only
        //default seeders//

         $this->call( ChurchTableSeeder::class );
        //test ----- hide to remove seeded members -----
         $this->call( ChurchDetailTableSeeder::class );
        //test
         $this->call( SubscriptionsTableSeeder::class );
        $this->call(BibleTableSeeder::class);
        $this->call(BibleVerseTableSeeder::class);
        //test ----- hide to remove seeded members -----
        // $this->call( EventsTableSeeder::class );
        //test
        // $this->call( UpcomingEventsTableSeeder::class );
        //test
        // $this->call( GalleryTableSeeder::class );
        //test
        // $this->call( SermonTableSeeder::class );
        //Test
        // $this->call( QuotesTableSeeder::class );
        //Test
        // $this->call( FundsTableSeeder::class );
        //Test
        // $this->call( UserprofilesTableSeeder::class );

    }
}
