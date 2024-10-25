<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\SendMailService;
use App\View\Components\WelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Blade;
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
            'Welcome to :app_name - Your Idea Management Hub!',
            ['app_name' => config('app.name')]
        );
        $this->sendMailService->sendEmail(
            Blade::renderComponent(new WelcomeEmail($user)),
            $subject,
            $user,
            true
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
}
