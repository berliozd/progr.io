<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompetitorsNote;

class CompetitorNoteController extends Controller
{
    public function destroy(string $id)
    {
        $note = CompetitorsNote::whereId($id)->first();
        $project = $note->competitor()->first()->project()->first();
        if ($project->user_id !== auth()->user()->id) {
            throw new \Exception('Not allowed');
        }
        $note->delete();
    }
}
