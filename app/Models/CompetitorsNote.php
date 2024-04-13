<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompetitorsNote extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'competitor_id', 'note_type_id', 'order'];

    public function type(): HasOne
    {
        return $this->hasOne(NotesType::class, 'id', 'note_type_id');
    }

    public function competitor()
    {
        return $this->belongsTo(Competitor::class);
    }
}
