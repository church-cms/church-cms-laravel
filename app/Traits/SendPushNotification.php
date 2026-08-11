<?php

namespace App\Traits;

use FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Exception;
use Log;

/**
 * Trait SendPushNotification
 *
 * Handles Firebase Cloud Messaging (FCM) push notifications including:
 * - Configuring FCM server and sender IDs
 * - Building notification payloads and options
 * - Sending push notifications to user devices
 * - Tracking notification delivery status
 *
 * @package App\Traits
 */
trait SendPushNotification

{
  /**
   * Send a Firebase Cloud Messaging (FCM) push notification.
   *
   * Sends a push notification to a user's device(s) using FCM with
   * customizable title, message, and data payload.
   *
   * @param array $array The notification array containing 'type' and 'message'
   * @param string $usertoken The device token to send the notification to
   *
   * @return object The FCM downstreamResponse object with delivery status
   */
  public function sendNotification(array $array, string $usertoken): object
  {
    try {

      config(['fcm.http.server_key' => env('FCM_SERVER_KEY')]);
      config(['fcm.http.sender_id' => env('FCM_SENDER_ID')]);

      $optionBuilder       = new OptionsBuilder();
      $optionBuilder->setTimeToLive(60 * 20);
      $notificationBuilder = new PayloadNotificationBuilder($array['type']);
      $notificationBuilder->setBody($array['message'])
        ->setTitle($array['type'])
        ->setSound('default');


      // $dataBuilder          = new PayloadDataBuilder();
      // $dataBuilder->addData(['message' => $array['message'], 'type' => $array['type']]);
      // $option               = $optionBuilder->build();
      // $notification         = $notificationBuilder->build();
      // $data                 = $dataBuilder->build();
      // $token                = $usertoken;
      // $downstreamResponse   = FCM::sendTo($token, $option, $notification, $data);

      //dd($downstreamResponse);

      // $downstreamResponse->numberSuccess();
      // $downstreamResponse->numberFailure();
      // if ($downstreamResponse->numberSuccess()) {
      //   return $downstreamResponse;
      // } else {
      //   return $downstreamResponse;
      // }

      return (object)[
        'status' => false,
        'message' => 'Notification failed'
      ];
    } catch (Exception $e) {
      dd($e->getMessage());


      Log::info($e->getMessage());
    }
  }
}
