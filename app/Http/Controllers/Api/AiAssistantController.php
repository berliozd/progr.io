<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;

class AiAssistantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ask(Request $request)
    {
        Log::debug('AI controller ask');
        $context = $request->get('context');
        $question = $request->get('question');
        $aiInsight = $this->askAi($context, $question);
        return ['response' => $aiInsight];
    }

    private function askAI(string $context, string $question): string
    {
        $client = $this->getClient();
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $context],
                ['role' => 'user', 'content' => $question]
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    private function getClient(): Client
    {
        $yourApiKey = config('services.openai.api_key');
        return \OpenAI::client($yourApiKey);
    }
}
