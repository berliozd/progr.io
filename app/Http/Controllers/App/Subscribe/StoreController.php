<?php

namespace App\Http\Controllers\App\Subscribe;

use App\Events\SubscriptionCreated;
use App\Http\Controllers\App\DashboardController;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Subscription;

class StoreController extends DashboardController
{
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                'paymentMethod' => 'required|string',
                'plan' => 'required|exists:products,id',
            ]);
            $selectedProduct = Product::find($validated['plan']);
            $paymentMethodeId = $validated['paymentMethod'];
            $subscriptionBuilder = auth()->user()
                ->newSubscription(
                    'default',
                    $selectedProduct->stripe_product_id
                );
            if (!empty($promoCode = $request->get('promoCode') ?? null)) {
                $subscriptionBuilder->couponId = $promoCode;
            }
            if (($trialPeriod = config('stripe.trial_period')) > 0) {
                $subscriptionBuilder->trialDays($trialPeriod);
            }
            /** @var Subscription $subscription */
            $subscription = $subscriptionBuilder->create($paymentMethodeId);

            SubscriptionCreated::dispatch($subscription);
        } catch (\Exception $e) {
            $products = Product::all();
            return Inertia::render('App/Subscribe/Create', [
                'products' => $products,
            ])->with('error', $e->getMessage());
        }

        return to_route('dashboard');
    }
}
