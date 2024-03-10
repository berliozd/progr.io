<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->subscribed() && Project::where('user_id', $request->user()->id)->count() >= 3) {
            return redirect()->route('dashboard')->withErrors(['msg' => 'app.need_subscription']);
        }
        return $next($request);
    }
}
