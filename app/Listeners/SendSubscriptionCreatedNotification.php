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
        $content = <<<HTML
Dear :user_name,
<br/>
<br/>
We are beyond excited to have you join our community of creators and innovators! 
Welcome to the premium version of :app_name, where you can now unlock the full potential of our unique SaaS solution designed specifically for people like you to track, define, and refine your project ideas.<br/>
<br/>
Upgrading means you're taking a significant step towards turning your ideas into reality, and we're thrilled to be a part of your creative journey.
<br/>
<br/>
As a premium user, you now have access to enhanced features and benefits to help you manage and grow your projects:<br/>
<br/>
<br/>

<strong>1: Unlimited Project Tracking:</strong><br>
<p>
With the premium version, you can track an unlimited number of projects simultaneously. 
This allows you to keep all your ideas organized in one place and focus on bringing your projects to life without any constraints.
</p>

<strong>2: Expanded AI Assistance:</strong><br>
<p>
Defining a project can sometimes be challenging, but with our AI technology integrated into the platform, 
you'll receive an unlimited number of AI credits to gain valuable insights such as potential users, potential features, benefits, 
and monetization strategies for your projects. 
Use these credits to shape your projects into successful ventures and make informed decisions.
</p>

<strong>3: Priority Support:</strong><br>
<p>
As a premium user, you'll receive priority support from our dedicated team. 
We're here to help you make the most of your experience with and ensure you have all the resources you need to succeed.
</p>
<br/>

<p>
Remember, the key to success is not just having great ideas, but also knowing how to execute them. 
We believe that :app_name will be an invaluable tool in your creative journey.
</p>

<br/>

<p>
To start enjoying your premium benefits, simply log in to your account: <a href=":app_url"><strong>:app_url</strong></a>
</p>

<p>
If you have any questions or need assistance, please don't hesitate to reach out to our support team. We're here to help you make the most of your experience.
</p>
<br/>
Thank you for choosing us, and here's to turning your ideas into reality!
<br/>
Best Regards,
<br/>
Didier
Founder of :app_name
HTML;

        $mj = new Client(
            config('services.mailjet.client_id'),
            config('services.mailjet.client_secret'),
            true,
            ['version' => 'v3.1']
        );
        $subject = __(
            'Welcome Aboard! Thank You for Upgrading to paid version of :app_name!',
            ['app_name' => config('app.name')]
        );
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => config('services.mailjet.mail_from'),
                        'Name' => config('app.name')
                    ],
                    'To' => [
                        [
                            'Email' => $user->email,
                            'Name' => $user->name
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => __(
                        $content,
                        [
                            'user_name' => $user->name,
                            'app_name' => config('app.name'),
                            'nb_fee_credits' => config('app.free-ai-credits'),
                            'app_url' => config('app.url')
                        ]
                    )
                ],
                [
                    'From' => [
                        'Email' => config('services.mailjet.mail_from'),
                        'Name' => config('app.name')
                    ],
                    'To' => [
                        [
                            'Email' => config('services.mailjet.mail_from'),
                            'Name' => config('app.name')
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => __(
                        ':name :email has just subscribed to paid version.',
                        ['name' => $user->name, 'email' => $user->email]
                    )
                ]
            ]
        ];
        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
