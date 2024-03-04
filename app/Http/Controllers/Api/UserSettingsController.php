<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::debug('index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::debug('store');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Log::debug('show user settings ' . $id);
        $user = auth()->user();
        if ($id !== (string)$user->id) {
            throw new \Exception('Not allowed');
        }
        return $user->settings;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::debug(sprintf('Udate user %s settings %s', $id, implode(',', $request->all())));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Log::debug('destroy');
    }
}
