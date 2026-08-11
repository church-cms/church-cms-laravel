<?php
namespace App\Traits;
use App\Models\Notes;

/**
 * Trait NotesProcess
 *
 * Manages note creation and storage including:
 * - Creating notes for various entities (sermons, events, etc.)
 * - Associating notes with churches and entities
 * - Tracking note creators and last updaters
 * - Supporting notes on multiple entity types
 *
 * @package App\Traits
 */
trait NotesProcess
{
    /**
     * Create a note for an entity.
     *
     * Creates and stores a note associated with a specific entity (sermon, event, etc.)
     * within a church, tracking who created and last updated the note.
     *
     * @param string $note The note content/text
     * @param int $church_id The church ID associated with the note
     * @param int $entity_id The ID of the entity the note is about
     * @param string $entity_name The type/class name of the entity (e.g., 'App\\Models\\Sermon')
     * @param int $created_by The user ID who created the note
     * @param int $updated_by The user ID who last updated the note
     *
     * @return \App\Models\Notes The created notes model instance
     */
    public function createNotes(
        string $note,
        int $church_id,
        int $entity_id,
        string $entity_name,
        int $created_by,
        int $updated_by
    ): Notes {
        $notes = new Notes;
        $notes->notes = $note;
        $notes->church_id = $church_id;
        $notes->entity_id = $entity_id;
        $notes->entity_name = $entity_name;
        $notes->created_by = $created_by;
        $notes->updated_by = $updated_by;
        $notes->save();

        return $notes;
    }
}
