<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class IdeasEmail extends Component
{
    public function __construct(public readonly Collection $ideas, public readonly User $user)
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.ideas-email');
    }
}
