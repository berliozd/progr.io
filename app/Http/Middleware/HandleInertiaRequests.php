<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $this->logReferer();

        \Log::debug('promotekit_referral : [' . ($_COOKIE['promotekit_referral'] ?? '') . ']');

        $locales = array_map('basename', glob(base_path('lang') . '/*', GLOB_ONLYDIR));
        $res = [
            ...parent::share($request),
            'app' => [
                'name' => config('app.name'),
                'locales' => $locales,
                'home_route' => config('app.home-route'),
                'free_nb_projects' => config('app.free-nb-projects'),
                'free_ai_credits' => config('app.free-ai-credits'),
                'auto_population_credits' => config('app.auto-population-credits'),
            ],
            'auth' => [
                'user' => $request->user(),
                'just_logged' => (bool)$request->session()->get('just_logged')
            ],
        ];
        return $res;
    }

    private function logReferer(): void
    {
        $_SERVER['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'] ?? '';
        $parsedUrl = parse_url($_SERVER['HTTP_REFERER']);
        $host = $parsedUrl['host'] ?? '';
        if (!empty($host) && $host !== config('app.url')) {
            \Log::debug('Referrer : ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
