<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $settings = auth()->user()->settings ?? '{}';
        $settings = json_decode($settings);
        $settings->receive_weekly_email = (bool)$request->input('receive_weekly_email');

        $request->user()->update([
            'settings' => json_encode($settings),
        ]);

        return back();
    }
}
