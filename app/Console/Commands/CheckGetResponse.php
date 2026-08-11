<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MailQueue;
use Exception;
use App\Traits\Common;

class CheckGetResponse extends Command
{
  use Common;
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'gego:checkgetresponse';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Check Get Response';

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

    try {
      $now       = date('Y-m-d');
      $lists = MailQueue::whereNotNull('fired_at')->wheredate('rule_checked_at', '=', $now)->get();
      $url = env('GETRESPONSE_URL') . '/contacts';
      $headers = array(
        'X-Auth-Token: api-key ' . env('GETRESPONSE_API_KEY'),
        'Content-Type: application/json',
      );
      $flag = 0;
      foreach ($lists as $list) {

        if ($list->is_read == 1) {
          //if already in no mail list
          $res_contact = $this->checkContacts($list->subscriber->email, $list->response->no_email_open_campaign_id, $headers);

          $campaignId = $list->response->email_open_campaign_id;
          $flag = 1;
        } else {
          $campaignId = $list->response->no_email_open_campaign_id;
        }

        $params = $this->getParams($list->subscriber->email, $list->subscriber->firstname, $campaignId);
        $res = $this->postResponse($url, $headers, $params);
        $res = json_decode($res, true);
        dump($res);
        /* if(count($res)>0)
                      {
                         if(($res['code']==1008) &&($flag==0))
                         {

                      
                          $res_contact =$this->checkContacts($list->subscriber->email,$campaign_id,$list->response->no_email_open_campaign_id); 

                           $params=$this->getParams($list->subscriber->email,$list->subscriber->firstname,$list->response->email_open_campaign_id);
                           $res=$this->postResponse($url,$headers,$params);
                           $res = json_decode($res,true); 
                            dump($res);

                         }
                      }*/
      }
    } catch (Exception $e) {
      //dd($e->getMessage());
    }
  }
  public function getParams($email, $firstname, $campaignId)
  {

    $params = array(
      'email' => $email,
      'name' => $firstname,
      "campaign"         => array(
        "campaignId" => $campaignId,
      ),

    );

    $params = json_encode($params);
    dump($params);
    return $params;
  }
  public function checkContacts($email, $campaign_id, $headers)
  {


    $contact_url = env('GETRESPONSE_URL') . '/contacts?query[email]=' . $email . '&query[campaignId]=' . $campaign_id;

    $res_contact = $this->getResponse($contact_url, $headers);
    $res_contact = json_decode($res_contact, true);
    //  dump($res_contact);
    if (count($res_contact) > 0) {
      if (isset($res_contact[0]['contactId'])) {
        $contact_delete_url = env('GETRESPONSE_URL') . '/contacts/' . $res_contact[0]['contactId'];
        //  dump($contact_delete_url);
        $res_contact_delete = $this->deleteResponse($contact_delete_url, $headers);
        $res_contact_delete = json_decode($res_contact_delete, true);
        // dump($res_contact_delete);
      }
    }
  }
}
