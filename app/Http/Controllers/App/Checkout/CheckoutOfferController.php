<?php

namespace App\Http\Controllers\App\Checkout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CheckoutOfferController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        $stripe = new StripeClient(
            config('cashier.secret')
        );
        $opts = [
            'line_items' => [
                [
                    'price' => config('cashier.offer_' . $id . '_price'),
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('checkout_offer_success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout_offer_cancel'),
        ];
        if ($request->user()->stripe_id) {
            $opts['customer'] = $request->user()->stripe_id;
        } else {
            $opts['customer_email'] = $request->user()->email;
            $opts['customer_creation'] = 'always';
        }
        if ($id !== 0) {
            $opts['discounts'] = [['coupon' => config('cashier.offer_' . $id . '_coupon')]];
        }
        $checkout_session = $stripe->checkout->sessions->create($opts);
        return redirect($checkout_session->url);
    }
}
