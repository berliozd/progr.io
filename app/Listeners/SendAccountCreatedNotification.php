<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\SendMailService;
use Illuminate\Auth\Events\Registered;
use Mailjet\Client;
use Mailjet\Resources;

readonly class SendAccountCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(private SendMailService $sendMailService)
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
        $subject = __(
            'Welcome to :app_name - Start Tracking Your Project Ideas Today!',
            ['app_name' => config('app.name')]
        );
        $content = $this->getContent();
        $content = __(
            $content,
            [
                'user_name' => $user->name,
                'app_name' => config('app.name'),
                'nb_fee_credits' => config('app.free-ai-credits'),
                'app_url' => config('app.url')
            ]
        );
        $this->sendMailService->sendEmail(
            $content,
            $subject,
            $user,
            true,
            __(
                ':name :email has just created an account.',
                ['name' => $user->name, 'email' => $user->email]
            )
        );
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
        return <<<HTML
Dear :user_name,
<br/>
<br/>
We're thrilled to have you on board! Welcome to :app_name, a unique SaaS solution designed to help you track, define, and refine your project ideas.
<br/>
<br/>
As a free account user, you have access to the following features:
<br/>
<br/>
<strong>1: Track Up to 3 Projects:</strong><br>
<p>Manage and monitor the progress of up to three projects simultaneously. 
</p>
<strong>2: Define Your Projects with AI Assistance:</strong><br>
<p>Receive AI credits to gain insights on potential users, features, benefits, and monetization strategies.
</p>
<strong>3: Use our NEW AUTO-POPULATION feature:</strong><br>

<p>Our AI-powered feature helps you quickly define and execute your ideas. Use 10 AI credits to run one project idea auto-population.
</p>
<br/>
<strong>Here's how it works: </strong><br/>
<p>One AI credit is used each time you request insights for a specific aspect of your project. Use these credits wisely to shape your projects into successful ventures!

</p>
<br/>
<p>
Remember, success comes from both great ideas and knowing how to execute them. We believe  will be a valuable tool for you.
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
    }
}
