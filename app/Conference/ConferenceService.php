<?php

namespace App\Conference;

use App\Models\VideoConference;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class ConferenceService
{

    protected $sid;
    protected $token;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');
    }

    public function storeData($request, $conferenceId, $slug)
    {
        $message = '';
        try {
            $client = new Client($this->sid, $this->token);
            $exists = $client->video->rooms->read(['uniqueName' => $slug]);
            if (empty($exists)) {
                $room = $client->video->rooms->create([
                    'uniqueName' => $slug,
                    'type' => 'group',
                    'recordParticipantsOnConnect' => true
                ]);

                $update = VideoConference::find($conferenceId);
                $update->room_id = $room->sid;
                $update->save();
                $status = true;
            }
        } catch (Exception $e) {
            $status = true;
            $message = $e->getMessage();
        }

        return array('status' => $status, 'message' => $message);
    }

    public function showDetails($roomName = '', $identity = '')
    {
        $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);

        $token->addGrant($videoGrant);

        return array('accessToken' => $token->toJWT());
    }

    public function closeConnection($conference, $id)
    {
        $message = '';
        try {
            $client = new Client($this->sid, $this->token);
            $composition = $client->video->compositions->create($conference->room_id, [
                'audioSources' => '*',
                'videoLayout' =>  array(
                    'grid' => array(
                        'video_sources' => array('*')
                    )
                ),
                'statusCallback' => url('video-conference/call-back'),
                'statusCallbackMethod' => "GET",
                'format' => 'mp4'
            ]);

            VideoConference::where('id', $id)->update(array('status' => 'stop', 'compose_id' => $composition->sid));
            $status = true;
        } catch (\Twilio\Exceptions\RestException $e) {
            $status = false;
            $message = $e->getMessage();
        }

        return array('status' => $status, 'message' => $message);
    }

    public function saveVideo($cid = '')
    {
        $client = new Client($this->sid, $this->token);
        $uri = "https://video.twilio.com/v1/Compositions/" . $cid . "/Media?Ttl=3600";
        $response = $client->request("GET", $uri);
        $mediaLocation = $response->getContent()["redirect_to"];
        @file_put_contents(public_path() . '/uploads/live-stream/' . $cid . '.mp4', fopen($mediaLocation, 'r'));
    }
}
