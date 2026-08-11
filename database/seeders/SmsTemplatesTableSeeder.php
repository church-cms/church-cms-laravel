<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SmsTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sms_templates')->insert([
            'id'            => '1',
            'name'          => 'Event',
            'content'       => 'Hi..Your event has been scheduled on :date at :location. For more details log in to church social App. https://churchcms.appsexpress.net',
            'status'        => '1',
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '2',
            'name'        => 'birthday_message',
            'template'     => 'Wishing you a happy birthday and a wonderful year.',
            'content'     => 'Wishing you a happy birthday and a wonderful year.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '3',
            'name'        => 'birthday_message',
            'template'     => 'May this special day bring you endless joy and tons of precious memories!Happy birthday.',
            'content'     => 'May this special day bring you endless joy and tons of precious memories!Happy birthday.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '4',
            'name'        => 'birthday_message',
            'template'    => 'Happy birthday! Here’s to a bright, healthy and exciting future!.',
            'content'     => 'Happy birthday! Here’s to a bright, healthy and exciting future!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '5',
            'name'        => 'birthday_message',
            'template'    => 'Wishing you a wonderful day and all the most amazing things on your Big Day!Happy birthday.',
            'content'     => 'Wishing you a wonderful day and all the most amazing things on your Big Day!Happy birthday.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '6',
            'name'        => 'birthday_message',
            'template'    => 'Happy birthday! May your day be filled with lots of love and happiness.',
            'content'     => 'Happy birthday! May your day be filled with lots of love and happiness.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '7',
            'name'        => 'birthday_message',
            'template'    => 'May this year surprise you with full of joy and happiness! Happy birthday!',
            'content'     => 'May this year surprise you with full of joy and happiness! Happy birthday!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '8',
            'name'        => 'birthday_message',
            'template'    => 'Sending you a birthday wish wrapped with all my love. Have a very happy birthday!',
            'content'     => 'Sending you a birthday wish wrapped with all my love. Have a very happy birthday!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '9',
            'name'        => 'birthday_message',
            'template'    => 'Many happy returns on your birthday today from all of us.We hope you have a wonderful day!',
            'content'     => 'Many happy returns on your birthday today from all of us.We hope you have a wonderful day!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '10',
            'name'        => 'birthday_message',
            'template'    => 'May your birthday be sprinkled with fun and laughter. Have a great day!',
            'content'     => 'May your birthday be sprinkled with fun and laughter. Have a great day!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '11',
            'name'        => 'birthday_message',
            'template'    => 'Happy Birthday!I hope you have a great day today and the year ahead is full of many blessings.',
            'content'     => 'Happy Birthday!I hope you have a great day today and the year ahead is full of many blessings.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '12',
            'name'        => 'anniversary_message',
            'template'    => 'Wishing a perfect pair a perfectly happy day.Happy anniversary.',
            'content'     => 'Wishing a perfect pair a perfectly happy day.Happy anniversary.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '13',
            'name'        => 'anniversary_message',
            'template'    => 'Sending love and good wishes to some of our very favorite people.',
            'content'     => 'Sending love and good wishes to some of our very favorite people.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '14',
            'name'        => 'anniversary_message',
            'template'    => 'Happy Anniversary to a couple who’s wished nothing but the best, always.',
            'content'     => 'Happy Anniversary to a couple who’s wished nothing but the best, always.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '15',
            'name'        => 'anniversary_message',
            'template'    => 'Happy Anniversary! May your love grow stronger and inspire all, and may life bless you with all the gifts.',
            'content'     => 'Happy Anniversary! May your love grow stronger and inspire all, and may life bless you with all the gifts.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '16',
            'name'        => 'anniversary_message',
            'template'    => 'May your everyday be as special as your wedding day. Wishing you a very happy anniversary.',
            'content'     => 'May your everyday be as special as your wedding day. Wishing you a very happy anniversary.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '17',
            'name'        => 'anniversary_message',
            'template'    => 'Wishing you a long and wonderful life together on this grand occasion.Happy anniversary.',
            'content'     => 'Wishing you a long and wonderful life together on this grand occasion.Happy anniversary. 
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '18',
            'name'        => 'anniversary_message',
            'template'    => 'Wonderful wishes to a wonderful couple!Happy anniversary.',
            'content'     => 'Wonderful wishes to a wonderful couple!Happy anniversary
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '19',
            'name'        => 'anniversary_message',
            'template'    => 'Wishing a perfect pair a perfectly happy day. Anniversary cheers!',
            'content'     => 'Wishing a perfect pair a perfectly happy day. Anniversary cheers!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '20',
            'name'        => 'anniversary_message',
            'template'    => 'May love continue to illuminate your lives for ever and ever.Happy Anniversary to both of you.',
            'content'     => 'May love continue to illuminate your lives for ever and ever.Happy Anniversary to both of you.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '21',
            'name'        => 'anniversary_message',
            'template'    => 'Happy Anniversary Just a little wish that your anniversary will be a wonderful and joys one.',
            'content'     => 'Happy Anniversary Just a little wish that your anniversary will be a wonderful and joys one.
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'          => '22',
            'name'        => 'anniversary_message',
            'template'    => 'Celebrate your special day with lots of love and joy.Happy Wedding Anniversary!!',
            'content'     => 'Celebrate your special day with lots of love and joy.Happy Wedding Anniversary!!
                          Thanks & Regards 
                          Administration Team',
            'status'      => '1',
            'created_at'  => date("Y-m-d H:i:s"),
            'updated_at'  => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'            => '23',
            'name'          => 'permission_credentials',
            'content'       => ':content',
            'status'        => '1',
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'            => '24',
            'name'          => 'reset_password',
            'content'       => 'Your password has been reset.Enter this password to login :token .',
            'status'        => '1',
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),
        ]);

        DB::table('sms_templates')->insert([
            'id'            => '25',
            'name'          => 'send_sms',
            'content'       => ':content',
            'status'        => '1',
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),
        ]);
    }
}