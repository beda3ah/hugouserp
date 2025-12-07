<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Attachment;
use App\Models\Note;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasNotesAttachments
{
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function pinnedNotes(): MorphMany
    {
        return $this->notes()->where('is_pinned', true);
    }

    public function addNote(string $content, string $type = 'general', bool $pinned = false): Note
    {
        return $this->notes()->create([
            'content' => $content,
            'type' => $type,
            'is_pinned' => $pinned,
            'branch_id' => auth()->user()?->branch_id,
            'created_by' => auth()->id(),
        ]);
    }
}
