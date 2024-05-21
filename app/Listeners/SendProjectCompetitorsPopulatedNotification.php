<?php

namespace App\Listeners;

use App\Events\ProjectCompetitorsPopulated;
use App\Services\SendMailService;

readonly class SendProjectCompetitorsPopulatedNotification
{

    public function __construct(private SendMailService $sendMailService)
    {
    }

    public function handle(ProjectCompetitorsPopulated $event)
    {
        \Log::info('Project competitors populated ' . $event->project->id);
        $subject = __(
            ':app_name - Competitors of your Project ":project_title" Have Been Populated!',
            ['app_name' => config('app.name'), 'project_title' => $event->project->title]
        );
        $this->sendMailService->sendEmail($this->getContent($event), $subject, $event->project->owner, false);
        \Log::info('"Project competitors populated" email sent to ' . $event->project->owner->email);


    }

    public function getContent($event): string
    {
        $content = <<<HTML
Dear :user_name,
<br/>
<br/>
Competitors for your project :project_title have been populated!<br/>
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
