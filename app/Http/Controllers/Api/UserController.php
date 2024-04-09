<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = auth()->user();
        if ($id !== (string)$user->id) {
            throw new \Exception('Not allowed');
        }
        return User::where('id', $id)->first()->toArray();
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

        $fieldName = $validated['field'];
        $user->$fieldName = $validated['value'];

        $user->save();
        return $user->toArray();
    }
}
