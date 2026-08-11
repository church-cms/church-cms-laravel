<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\PushEvent;
use App\Models\Quote;
use Exception;
use Log;

class CheckQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gego:checkquote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Quote';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try
        {
            $quotes = Quote::WhereRaw("DATE_FORMAT(publish_on, '%y-%m-%d') = DATE_FORMAT(now() ,'%y-%m-%d')")->get();

            foreach($quotes as $quote)
            {   
                if(date('Y-m-d H:i:s',strtotime($quote->publish_on)) <= date('Y-m-d H:i:s'))
                {
                    $update_quote               = Quote::where('id',$quote->id)->first();

                    $update_quote->published_at = date('Y-m-d H:i:s');
                    $update_quote->status       = 1; 

                    $update_quote->save();

                    $data=[];

                    $data['church_id']  =   $quote->church_id;
                    $data['message']    =   'New Quote Added';
                    $data['type']       =   'quote';

                    event(new PushEvent($data));
                }
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
}