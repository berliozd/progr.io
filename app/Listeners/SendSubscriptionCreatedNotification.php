<?php

namespace App\Listeners;

use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Subscription;
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
        \Log::info(json_encode($event->payload));
//        $this->sendEmail($event->getSubscription());
    }

    private function sendEmail(Subscription $subscription): void
    {
        $mj = new Client(
            config('services.mailjet.client_id'),
            config('services.mailjet.client_secret'),
            true,
            ['version' => 'v3.1']
        );
        $userData = $subscription->user()->get(['name', 'email'])[0];
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => config('services.mailjet.mail_from'),
                        'Name' => "Me"
                    ],
                    'To' => [
                        [
                            'Email' => $userData['email'],
                            'Name' => 'You'
                        ]
                    ],
                    'Subject' => 'Thanks for your subscription.',
                    'HTMLPart' => __(
                        'Congratulation :name! You have just subscribed to ......',
                        ['name' => $userData['name']]
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
                        ['name' => $userData['name'], 'email' => $userData['email']]
                    )
                ]
            ]
        ];
        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
