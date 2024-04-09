<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'url', 'project_id'];

    public function notes()
    {
        return $this->hasMany(CompetitorsNote::class);
    }
}
