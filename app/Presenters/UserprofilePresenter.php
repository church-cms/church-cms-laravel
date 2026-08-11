<?php
namespace App\Presenters;

use Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class UserprofilePresenter extends Presenter
{

  public function getAge($date_of_birth)
  {        	

	$to     = date('Y', strtotime($date_of_birth));
    $now    = Carbon::now();
    $from   = date('Y', strtotime($now));
    $age    = $from-$to;
		return ($age);
  }

  public function get($created_at)
  {        	

	  $to     = date('Y-m-d', strtotime($created_at));
    $now    = Carbon::now();
    $from   = date('Y-m-d', strtotime($now));
    $age    = $from-$to;
		return ($age);
  }

  public function getLongTimeMemberDays($created_at)
    {   
      $now    = Carbon::now()->format('Y-m-d');
      $datetime1 = strtotime(date('Y-m-d', strtotime($created_at)));
      $datetime2 = strtotime(date('Y-m-d', strtotime($now)));

      $secs = $datetime2 - $datetime1;// == <seconds between the two times>
      $days = $secs / 86400;

      return $days;
    }
}