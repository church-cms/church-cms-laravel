<?php
namespace App\Traits;
use App\Models\Favorite;

/**
 * Trait FavoriteProcess
 *
 * Manages user favorite/bookmark functionality including:
 * - Creating favorite records for entities (sermons, events, etc.)
 * - Associating favorites with churches and users
 * - Supporting favoriting of multiple entity types
 *
 * @package App\Traits
 */
trait FavoriteProcess
{

    /**
     * Create a favorite record for an entity.
     *
     * Adds an entity to a user's favorites list, allowing them to quickly access frequently used items.
     *
     * @param int $church_id The church ID for the favorite record
     * @param int $user_id The user ID who is favoriting the entity
     * @param int $entity_id The ID of the entity being favorited
     * @param string $entity_name The type/class name of the entity (e.g., 'App\\Models\\Sermon')
     *
     * @return bool True if the favorite was successfully created
     */
    public function favoriteProcess(int $church_id, int $user_id, int $entity_id, string $entity_name): bool {
        $favorite = new Favorite;
        $favorite->church_id = $church_id;
        $favorite->user_id = $user_id;
        $favorite->entity_id = $entity_id;
        $favorite->entity_name = $entity_name;

        $favorite->save();

        return true;
    }
}
