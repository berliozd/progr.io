<?php

namespace App\Http\Controllers\App\Subscribe;

use App\Http\Controllers\App\DashboardController;
use Auth;
use Illuminate\Http\Request;
use Laravel\Cashier\SubscriptionBuilder;

class CheckoutController extends DashboardController
{
    public function __invoke(Request $request)
    {
        /** @var SubscriptionBuilder $subscriptionBuilder */
        $subscriptionBuilder = Auth::user()->newSubscription('default', config('cashier.basic_price'));

        $subscriptionBuilder->allowPromotionCodes();

        if (($trialPeriod = config('cashier.trial_period')) > 0) {
            $subscriptionBuilder->trialDays((int)$trialPeriod);
        }
        if (isset($_COOKIE['promotekit_referral'])) {
            $subscriptionBuilder->withMetadata(['promotekit_referral' => $_COOKIE['promotekit_referral']]);
        }

        return $subscriptionBuilder->checkout();
    }
}
