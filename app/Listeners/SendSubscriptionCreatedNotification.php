<?php

namespace App\Listeners;

use App\Models\User;
use Laravel\Cashier\Events\WebhookHandled;
use Mailjet\Client;
use Mailjet\Resources;

class SendSubscriptionCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookHandled $event): void
    {
        \Log::info($event->payload['type']);
        if ('customer.subscription.created' !== $event->payload['type']) {
            \Log::info($event->payload['type'] . ' so returning.');
            return;
        }
        $stripeCustomer = $event->payload['data']['object']['customer'];
        \Log::info($stripeCustomer);
        $user = User::where('stripe_id', $stripeCustomer)->first();
        $this->sendEmail($user);
    }

    private function sendEmail(User $user): void
    {
        \Log::info('Sending email to ' . $user->email);
        $mj = new Client(
            config('services.mailjet.client_id'),
            config('services.mailjet.client_secret'),
            true,
            ['version' => 'v3.1']
        );
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => config('services.mailjet.mail_from'),
                        'Name' => "Me"
                    ],
                    'To' => [
                        [
                            'Email' => $user->email,
                            'Name' => 'You'
                        ]
                    ],
                    'Subject' => 'Thanks for your subscription.',
                    'HTMLPart' => __(
                        'Congratulation :name! You have just subscribed to ......',
                        ['name' => $user->name]
                    )
                ],
                [
                    'From' => [
                        'Email' => config('services.mailjet.mail_from'),
                        'Name' => 'Me'
                    ],
                    'To' => [
                        [
                            'Email' => config('services.mailjet.mail_from'),
                            'Name' => 'You'
                        ]
                    ],
                    'Subject' => 'New subscription',
                    'HTMLPart' => __(
                        ':name :email has just subscribed to ......',
                        ['name' => $user->name, 'email' => $user->email]
                    )
                ]
            ]
        ];
        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
