<?php

namespace App\Traits;

use App\Models\Sermon;
use App\Models\Vote;

/**
 * Trait VoteProcess
 *
 * Manages user voting/liking functionality including:
 * - Creating like votes on entities (sermons, etc.)
 * - Creating unlike votes with automatic like removal
 * - Tracking votes by church, user, and entity
 * - Supporting voting on multiple entity types
 *
 * @package App\Traits
 */
trait VoteProcess
{
    /**
     * Create a like vote on an entity.
     *
     * Allows a user to like an entity. Automatically removes any previous unlike vote.
     * Returns the vote result message.
     *
     * @param object $request The request object containing entity_id
     * @param int $church_id The church ID associated with the vote
     * @param int $user_id The user ID who is voting
     *
     * @return array Array containing 'message' key with vote result
     */
    public function createlikeVote(object $request, int $church_id, int $user_id): array
    {
        $sermon = Sermon::where([['church_id', $church_id], ['id', $request->entity_id]])->first();

        if ($sermon != null) {
            if ($church_id == $sermon->church_id) {
                $existing_vote = Vote::where([['church_id', $church_id], ['user_id', $user_id], ['entity_id', $sermon->id], ['unlike', 1]])->first();

                if ($existing_vote != null) {
                    $existing_vote->delete();
                }
                $existing_vote = Vote::where([['church_id', $church_id], ['user_id', $user_id], ['entity_id', $request->entity_id], ['like', 1]])->first();
                if (count($existing_vote) == 0) {
                    $like = "1";
                    $unlike = "0";

                    $vote = new Vote;

                    $vote->church_id    =   $church_id;
                    $vote->user_id      =   $user_id;
                    $vote->entity_id    =   $request->entity_id;
                    $vote->entity_name  =   get_class($sermon);
                    $vote->like         =   $like;
                    $vote->unlike       =   $unlike;

                    $vote->save();

                    $res['message'] = 'You have liked this sermon';
                } else {
                    $existing_vote->delete();
                    $res['message'] = 'Like Deleted';
                }
            } else {
                $res['message'] = 'Invalid';
            }
        } else {
            $res['message'] = 'Invalid';
        }
        return $res;
    }


    /**
     * Create an unlike/dislike vote on an entity.
     *
     * Allows a user to dislike an entity. Automatically removes any previous like vote.
     * Returns the vote result message.
     *
     * @param object $request The request object containing entity_id
     * @param int $church_id The church ID associated with the vote
     * @param int $user_id The user ID who is voting
     *
     * @return array Array containing 'message' key with vote result
     */
    public function createunlikeVote(object $request, int $church_id, int $user_id): array
    {
        $sermon = Sermon::where([['church_id', $church_id], ['id', $request->entity_id]])->first();

        if ($sermon != null) {
            if ($church_id == $sermon->church_id) {
                $existing_vote = Vote::where([['church_id', $church_id], ['user_id', $user_id], ['entity_id', $sermon->id], ['like', 1]])->first();

                if ($existing_vote != null)

                    $existing_vote->delete();

                $existing_vote = Vote::where([['church_id', $church_id], ['user_id', $user_id], ['entity_id', $sermon->id], ['unlike', 1]])->first();

                if ($existing_vote != null) {
                    $unlike = "1";
                    $like = "0";

                    $vote = new Vote;

                    $vote->church_id    =   $church_id;
                    $vote->user_id      =   $user_id;
                    $vote->entity_id    =   $request->entity_id;
                    $vote->entity_name  =   get_class($sermon);
                    $vote->like         =   $like;
                    $vote->unlike       =   $unlike;

                    $vote->save();

                    $res['message'] = 'You have disliked this sermon';
                } else {
                    $existing_vote->delete();
                    $res['message'] = 'Dislike Deleted';
                }
            } else {
                $res['message'] = 'Invalid';
            }
        } else {
            $res['message'] = 'Invalid';
        }
        return $res;
    }
}
