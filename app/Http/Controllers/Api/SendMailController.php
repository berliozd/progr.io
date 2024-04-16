<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mailjet\Client;
use Mailjet\Resources;

class SendMailController extends Controller
{
    public function send(Request $request)
    {
        try {
            $subject = $request->get('subject');
            $recipient = $request->get('recipient');
            $content = $request->get('content');
            $this->sendEmail($subject, $recipient, $content);
            return ['response' => ['success' => true]];
        } catch (\Exception $e) {
            return ['response' => ['success' => false, 'message' => $e->getMessage()]];
        }
    }

    private function sendEmail(string $subject, string $recipient, string $content): void
    {
        $mj = new Client(
            config('services.mailjet.client_id'),
            config('services.mailjet.client_secret'),
            true,
            ['version' => 'v3.1']
        );
        $body = [
            'Messages' => [
                [
//                    'From' => ['Email' => 'no-reply@progr.io'],
                    'From' => ['Email' => 'berliozd@gmail.com'],
                    'To' => [['Email' => $recipient]],
                    'Subject' => $subject,
                    'HTMLPart' => $content
                ]
            ]
        ];
        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
