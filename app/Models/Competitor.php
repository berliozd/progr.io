<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'url'];

    public function notes()
    {
        return $this->hasMany(CompetitorsNote::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'competitors_projects');
    }
}
