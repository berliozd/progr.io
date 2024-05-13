<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AIService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(private readonly AIService $aiService)
    {
    }

    public function ask(Request $request): array
    {
        $context = $request->get('context');
        $question = $request->get('question');
        return ['response' => $this->aiService->getInsight($context, $question)];
    }

    public function askNote(Request $request): array
    {
        $projectTitle = $request->get('title');
        $projectDescription = $request->get('description');
        $noteTypeCode = $request->get('noteTypeCode');
        $isCompetitor = $request->get('competitor') == 1;
        return [
            'response' => $this->aiService->getNote(
                $projectTitle,
                $projectDescription,
                $noteTypeCode,
                $isCompetitor
            )
        ];
    }

    public function askCompetitors(Request $request): array
    {
        $projectTitle = $request->get('title');
        $projectDescription = $request->get('description');
        return [
            'response' => $this->aiService->getCompetitors(
                $projectTitle,
                $projectDescription
            )
        ];
    }
}
