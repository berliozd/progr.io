<?php

namespace App\Services;

use App\Models\User;
use Mailjet\Client;
use Mailjet\Resources;

class SendMailService
{
    public function sendEmail(string $content, string $subject, User $user, bool $sendToSupport = true): void
    {
        $mj = new Client(
            config('services.mailjet.client_id'),
            config('services.mailjet.client_secret'),
            true,
            ['version' => 'v3.1']
        );
        $messages = [
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
                'HTMLPart' => $content
            ]
        ];
        if ($sendToSupport) {
            $messages[] = [
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
                'HTMLPart' => $content
            ];
        }
        $body = ['Messages' => $messages];
        $mj->post(Resources::$Email, ['body' => $body]);
    }
}