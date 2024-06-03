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

    public function askSharingEmailContent(Request $request): array
    {
        $id = (int)$request->get('id');
        $title = $request->get('title');
        $description = $request->get('description');
        return ['response' => $this->aiService->getSharingEmailContent($id, $title, $description)];
    }


    public function askIdeas(Request $request): array
    {
        return [
            'response' => $this->aiService->getIdeas($request->get('context'))
        ];
    }

    public function askNote(Request $request): array
    {
        return [
            'response' => $this->aiService->getNote(
                $request->get('title'),
                $request->get('description'),
                $request->get('noteTypeCode'),
                $request->get('competitor') == 1
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
