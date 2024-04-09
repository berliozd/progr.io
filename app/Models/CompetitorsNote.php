<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitorsNote extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'competitor_id', 'notes_type_id'];

    public function type(): HasOne
    {
        return $this->hasOne(NotesType::class, 'id', 'note_type_id');
    }
}
