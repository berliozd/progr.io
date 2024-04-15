<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'visibility',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, (new User)->getForeignKey());
    }

    public function getUpdatedAtAttribute($value): false|int
    {
        return strtotime($value);
    }

    public function competitors(): HasMany
    {
        return $this->hasMany(Competitor::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ProjectsNote::class);
    }
}
