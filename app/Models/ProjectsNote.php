<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectsNote extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'note_type_id', 'content', 'order'];

    public function type(): HasOne
    {
        return $this->hasOne(NotesType::class, 'id', 'note_type_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
