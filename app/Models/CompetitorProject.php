<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorProject extends Model
{
    use HasFactory;

    protected $table = 'competitors_projects';

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
