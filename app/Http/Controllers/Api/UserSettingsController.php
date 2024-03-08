<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
    public function show(string $id)
    {
        $user = auth()->user();
        if ($id !== (string)$user->id) {
            throw new \Exception('Not allowed');
        }
        return $user->settings;

    }

    public function update(Request $request, string $id)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($id !== (string)$user->id) {
            throw new \Exception('Not allowed');
        }

        $validated = $request->validate([
            'field' => 'required|string',
            'value' => 'required',
        ]);

        $settings = json_decode($user->settings, true);
        $settings[$validated['field']] = $validated['value'];
        $user->settings = json_encode($settings);
        $user->save();
        return $user->settings;
    }
}
