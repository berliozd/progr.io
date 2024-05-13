<?php

namespace App\Listeners;

use App\Events\UserAuthenticated;
use Illuminate\Http\Request;

readonly class FlagUserJustAuthenticated
{
    public function __construct(private Request $request)
    {
    }

    public function handle(UserAuthenticated $event): void
    {
        $this->request->session()->flash('just_logged');
    }
}
