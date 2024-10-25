<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WelcomeEmail extends Component
{
    public function __construct(public readonly User $user)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.welcome-email');
    }
}
