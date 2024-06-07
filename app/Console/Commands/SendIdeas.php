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
    public function __construct(private readonly SendMailService $mailService,)
    {
        Parent::__construct();
    }

    protected $signature = 'ideas:send';

    protected $description = 'Send Ideas by email.';

    public function handle()
    {
        \Log::info('Start sending ideas by email');

        $projects = Project::Where('auto_populated_at', '!=', null)
            ->where('visibility', ProjectsVisibility::where('code', 'public')->pluck('id')->first())
            ->has('category')
            ->limit(5)
            ->orderBy('updated_at', 'desc')
            ->with('category');
        $users = User::where('email', 'like', '%berliozd@gmail.com%')->get();
        foreach ($users as $user) {
            $this->mailService->sendEmail(
                Blade::renderComponent(new IdeasEmail($projects->get(), $user)),
                'Your Weekly Dose of Inspiration - 5 New Ideas from ' . config('app.name') . '!',
                $user
            );
        }

        \Log::info('End sending ideas by email');
    }
}
