<?php
/**
 * Trait for processing common
 */
namespace App\Traits;

use App\Models\MailingList;
use App\Models\Subscribers;
use Exception;
use Log;

/**
 * Trait for subscriber and mailing list management
 *
 * Provides functionality for:
 * - Creating new subscriber accounts
 * - Attaching subscribers to mailing lists
 * - Managing newsletter subscriptions
 *
 * @package App\Traits
 */
trait SubscriberProcess
{
    /**
     * Create a subscriber and attach to a mailing list.
     *
     * Creates a new subscriber if they don't already exist, and attaches
     * them to the mailing list identified by the given slug.
     *
     * @param string $slug The mailing list slug identifier
     * @param object $request The request object containing subscriber data (firstname, lastname, email, aff, source, active)
     * @param int $church_id The church ID associated with the subscriber
     *
     * @return Subscribers|null The created or existing subscriber model
     */
    public function createSubscriberAttachToMailingList(string $slug, object $request, int $church_id): ?Subscribers {
        $subscriber = [];
        try {
            $subscriber = Subscribers::where('email', $request->email)->first();
            if (count($subscriber) == 0) {
                $subscriber = Subscribers::create([
                    'church_id' =>  $church_id,
                    'firstname' =>  $request->firstname,
                    'lastname'  =>  $request->lastname,
                    'email'     =>  $request->email,
                    'aff'       =>  $request->aff,
                    'source'    =>  $request->source,
                    'active'    =>  $request->active,
                ]);

                $mailinglist = MailingList::where('slug',$slug)->first();

                $mailinglist->subscribers()->attach($subscriber->id);

                $mailinglist->save();
            }
            return $subscriber;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}
