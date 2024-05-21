<?php

namespace App\Listeners;

use App\Events\ProjectPopulated;
use App\Services\SendMailService;

readonly class SendProjectPopulatedNotification
{

    public function __construct(private SendMailService $sendMailService)
    {
    }

    public function handle(ProjectPopulated $event)
    {
        \Log::info('Project populated ' . $event->project->id);
        $subject = __(
            ':app_name - Your Project ":project_title" Has Been Populated!',
            ['app_name' => config('app.name'), 'project_title' => $event->project->title]
        );
        $this->sendMailService->sendEmail($this->getContent($event), $subject, $event->project->owner, false);

    }

    public function getContent($event): string
    {
        $content = <<<HTML
Dear :user_name,
<br/>
<br/>
Your project :project_title has been populated!<br/>
<br/>
Have a look at it here:
<a href=":app_url/project/:project_id"><strong>:app_url/project/:project_id</strong></a>
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
        return __(
            $content,
            [
                'project_id' => $event->project->id,
                'project_title' => $event->project->title,
                'user_name' => $event->project->owner->name,
                'app_name' => config('app.name'),
                'app_url' => config('app.url')
            ]
        );
    }

}
