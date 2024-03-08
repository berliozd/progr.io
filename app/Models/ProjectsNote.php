<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectsNote extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'note_type_id', 'content'];
}
