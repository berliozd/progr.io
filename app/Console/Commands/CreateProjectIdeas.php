<?php

namespace App\Console\Commands;

use App\Models\AutoPopulations;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectsStatus;
use App\Models\ProjectsVisibility;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Console\Command;

class CreateProjectIdeas extends Command
{
    public function __construct(private readonly AIService $aiService)
    {
        Parent::__construct();
    }

    protected $signature = 'project:create_project_ideas {keywords?}';

    protected $description = 'Create project ideas.';

    public function handle(): void
    {
        \Log::info('Create project ideas');
        $ideas = $this->aiService->getIdeas($this->getContext(), 1);
        $userId = $this->getUserId();
        $statusId = (int)ProjectsStatus::where('label', 'New')->first()->id;
        $visibilityId = (int)ProjectsVisibility::where('code', 'public')->first()->id;
        $autoPopulateId = (int)AutoPopulations::where('code', 'on')->first()->id;
        $this->createProject($this->extractIdea($ideas), $statusId, $visibilityId, $autoPopulateId, $userId);
        $this->createProject($this->extractIdea($ideas), $statusId, $visibilityId, $autoPopulateId, $userId);
        $this->createProject($this->extractIdea($ideas), $statusId, $visibilityId, $autoPopulateId, $userId);
        $this->createProject($this->extractIdea($ideas), $statusId, $visibilityId, $autoPopulateId, $userId);
        $this->createProject($this->extractIdea($ideas), $statusId, $visibilityId, $autoPopulateId, $userId);
        \Log::info('Creating project ideas done!');
    }

    private function createProject(
        array $idea,
        int $statusId,
        int $visibilityId,
        int $autoPopulateId,
        int $userId
    ): void {
        Project::create([
            'title' => $idea[0],
            'description' => $idea[1],
            'status' => $statusId,
            'visibility' => $visibilityId,
            'auto_population' => $autoPopulateId,
            'user_id' => $userId,
        ])->save();
    }

    private function getUserId(): int
    {
        $userIds = User::where('email', 'like', '%fakedata%')
            ->whereNotNull('stripe_id')->pluck('id')->toArray();
        $userId = $userIds[array_rand($userIds)];
        \Log::info('for user ' . $userId);
        return (int)$userId;
    }

    private function extractIdea(array &$ideas): array
    {
        $keys = array_keys($ideas);
        $index = rand(0, count($ideas) - 1);
        $idea = $ideas[$keys[$index]];
        unset($ideas[$keys[$index]]);
        return $idea;
    }

    public function getContext(): string
    {
        $keywords = $this->argument('keywords');
        if (!empty($keywords)) {
            $context = $keywords;
        } else {
            $categories = Category::all()->pluck('code')->toArray();
            $context = $categories[rand(0, count($categories) - 1)];
        }
        return $context;
    }
}
