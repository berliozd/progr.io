<?php

namespace App\Http\Controllers\App\Checkout;

use App\Http\Controllers\App\DashboardController;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Stripe\Checkout\Session;
use Stripe\LineItem;
use Stripe\Stripe;

class OfferSuccessController extends DashboardController
{
    public function __invoke(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId === null) {
            return;
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        // Get user from stripe session
        $user = User::where('email', $session->customer_details->email)->first();
        $nbCreditsToAdd = $this->getNbCredits($session);
        $user->update([
            'nb_credits' => $user->nb_credits + $nbCreditsToAdd, 'updated_at' => now(),
            'stripe_id' => $session->customer
        ]);

        return redirect(route('dashboard'));
    }

    private function getProductId(Session $session): string
    {
        $sess = new Session($session->id);
        Stripe::setApiKey(config('cashier.secret'));
        $items = $sess->allLineItems($session->id);
        /** @var LineItem $item */
        foreach ($items as $item) {
            return $item->price->id;
        }
        return '';
    }

    private function getNbCredits(Session $session): int
    {
        $productId = $this->getProductId($session);
        return match ($productId) {
            config('cashier.offer_0_price') => config('cashier.offer_0_nb_credits'),
            config('cashier.offer_1_price') => config('cashier.offer_1_nb_credits'),
            config('cashier.offer_2_price') => config('cashier.offer_2_nb_credits'),
            default => 0
        };
    }
}
