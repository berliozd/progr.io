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
            ],
            'auth' => [
                'user' => $request->user(),
                'just_logged' => (bool)$request->session()->get('just_logged')
            ],
        ];
        if (!empty($request->user())) {
            $product = null;
            $subscriptionGracePeriod = false;
            $subscriptionPremium = false;
            $subscriptionEndDate = null;
            if (!empty($subscription = auth()->user()?->subscription())) {
                $product = Product::where('stripe_product_id', $subscription->stripe_price)->first();
                $subscriptionGracePeriod = $subscription->onGracePeriod();
                /** @var Carbon $subscriptionEndDate */
                $subscriptionEndDate = $subscription->ends_at;
                $subscriptionPremium = auth()->user()?->subscribedToPrice(config('cashier.premium_price'));
            }
            $res['auth']['subscription'] = [
                'is_subscribed' => !empty($subscription),
                'on_grace_period' => $subscriptionGracePeriod,
                'is_premium' => $subscriptionPremium,
                'end_date' => $subscriptionEndDate ? $subscriptionEndDate->timestamp : 0,
                'product' => $product
            ];
        }
        return $res;
    }
}
