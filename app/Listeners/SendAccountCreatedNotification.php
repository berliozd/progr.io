<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Mailjet\Client;
use Mailjet\Resources;

class SendAccountCreatedNotification
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
    public function handle(Registered $event): void
    {
        $this->sendEmail($event->user);
    }

    private function sendEmail($user): void
    {
        $content = $this->getContent();
        $mj = new Client(
            config('services.mailjet.client_id'),
            config('services.mailjet.client_secret'),
            true,
            ['version' => 'v3.1']
        );
        $subject = __(
            'Welcome to :app_name - Start Tracking Your Project Ideas Today!',
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
                        ':name :email has just created an account.',
                        ['name' => $user->name, 'email' => $user->email]
                    )
                ]
            ]
        ];
        $mj->post(Resources::$Email, ['body' => $body]);

        $this->addToContactList($user);
    }

    public function addToContactList(User $user): void
    {
        try {
            $params = [
                'Contacts' => [
                    [
                        'Email' => $user->email,
                        'IsExcludedFromCampaigns' => false,
                        'Name' => $user->name,
                        'Properties' => 'Object'
                    ]
                ],
                'ContactsLists' => [['Action' => 'addforce', 'ListID' => config('services.mailjet.list_id')]]
            ];
            $mj = new Client(
                config('services.mailjet.client_id'),
                config('services.mailjet.client_secret'),
                true,
                ['version' => 'v3']
            );
            $response = $mj->post(Resources::$ContactManagemanycontacts, ['body' => $params]);
            \Log::info('added to contact list : ' . $response->success());
            \Log::info($response->getData());
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $content = <<<HTML
Dear :user_name,
<br/>
<br/>
We are thrilled to have you on board!
<br/>
<br/>
Welcome to :app_name, a unique SaaS solution designed specifically for people like you to track, define, and refine your project ideas.
<br/>
<br/>
Our mission is to help you transform your ideas into reality, and we're excited to be a part of your creative journey.
<br/>
<br/>
<br/>
As a free account user, you have access to the following features:
<br/>
<br/>
<strong>1: Track Up to 3 Projects:</strong><br>
<p>You can now easily manage and monitor the progress of up to three projects simultaneously. 
Our platform allows you to keep all your ideas organized in one place, making it easier for you to focus on what matters most - bringing your projects to life.
</p>
<strong>2: Define Your Projects with AI Assistance:</strong><br>

<p>We understand that defining a project can sometimes be challenging. That's why
we've integrated AI technology into our platform. With your free account, you'll receive :nb_fee_credits AI credits. 
Each credit allows you to gain valuable insights such as potential users, potential features, benefits, and monetization strategies for your project.
</p>
<br/>
<strong>Here's how it works: </strong><br/>
<p>One AI credit is used each time you request insights for a specific aspect of your project (like potential users or features). 
This means you can get a comprehensive understanding of how your project will impact users. Use these credits wisely to shape your projects into successful ventures!
</p>
<br/>
<p>
Remember, the key to success is not just having great ideas, but also knowing how to execute them. We believe that :app_name will be a valuable tool for you.
</p>
Create your first project today:
<a href=":app_url"><strong>:app_url</strong></a>
<p>
If you have any questions or need assistance, please don't hesitate to reach out to our support team. We're here to help
you make the most of your experience with :app_name.
</p>
<br/>
Thank you for choosing us, and here's to turning your ideas into reality!
<br/>
Best Regards,
<br/>
Didier
Founder of :app_name
HTML;
        return $content;
    }
}
