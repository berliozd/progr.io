<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectsVisibility;
use App\Models\User;
use App\Services\SendMailService;
use App\View\Components\IdeasEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;

class SendIdeas extends Command
{
    private const int NB_IDEAS = 5;

    public function __construct(private readonly SendMailService $mailService,)
    {
        Parent::__construct();
    }

    protected $signature = 'project:send_ideas';

    protected $description = 'Send Ideas by email.';

    public function handle()
    {
        \Log::info('Start sending ideas by email');

        $projects = Project::Where('auto_populated_at', '!=', null)
            ->where('visibility', ProjectsVisibility::where('code', 'public')->pluck('id')->first())
            ->has('category')
            ->limit(self::NB_IDEAS)
            ->orderBy('updated_at', 'desc')
            ->with('category');
        $users = User::all();
        foreach ($users as $user) {
            if (!$this->canSendEmailToUser($user)) {
                continue;
            }
            $this->mailService->sendEmail(
                Blade::renderComponent(new IdeasEmail($projects->get(), $user)),
                sprintf('Your Weekly Dose of Inspiration - %s New Ideas from %s!', self::NB_IDEAS, config('app.name')),
                $user
            );
        }

        \Log::info('End sending ideas by email');
    }

    private function canSendEmailToUser(User $user): bool
    {
        $settings = json_decode($user->settings, true);
        return $settings['receive_weekly_email'] ?? false;
    }
}
