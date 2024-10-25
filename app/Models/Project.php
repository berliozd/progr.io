<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'visibility',
        'auto_population',
        'auto_populated_at',
        'add_competitors_failure'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, (new User)->getForeignKey());
    }

    public function getUpdatedAtAttribute($value): false|int
    {
        return strtotime($value);
    }

    public function competitors(): BelongsToMany
    {
        return $this->belongsToMany(Competitor::class, 'competitors_projects')->withPivot('id', 'order');

    }

    public function notes(): HasMany
    {
        return $this->hasMany(ProjectsNote::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function metaDescription(): HasOne
    {
        return $this->hasOne(ProjectMeta::class, 'project_id')->where(
            'type',
            MetaType::where('name', 'description')->first()->id
        );
    }

    public function metaKeywords(): HasOne
    {
        return $this->hasOne(ProjectMeta::class, 'project_id')->where(
            'type',
            MetaType::where('name', 'keywords')->first()->id
        );
    }
}
