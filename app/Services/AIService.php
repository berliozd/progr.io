<?php

namespace App\Services;

use App\Models\Category;
use OpenAI;
use OpenAI\Client;

class AIService
{
    const string GPT_ENGINE_VERSION = 'gpt-3.5-turbo';
    private const int NB_COMPETITORS = 3;


    private function getClient(): Client
    {
        $yourApiKey = config('services.openai.api_key');
        return OpenAI::client($yourApiKey);
    }

    public function getInsight(string $context, string $question): string
    {
        \Log::info($question);
        $client = $this->getClient();
        $response = $client->chat()->create([
            'model' => self::GPT_ENGINE_VERSION,
            'messages' => [
                ['role' => 'user', 'content' => $context],
                ['role' => 'user', 'content' => $question]
            ],
        ]);
        $aiInsight = $response->choices[0]->message->content;
        \Log::info($aiInsight);
        return $aiInsight;
    }

    public function getNote(
        string $projectTitle,
        string $projectDescription,
        string $noteTypeCode,
        bool $competitor = false
    ): string {
        $context = $this->getContext($projectTitle, $projectDescription);
        $question = $this->wrapNoteQuestion($noteTypeCode, $context, $competitor);
        return $this->getInsight($context, $question);
    }

    public function getCompetitors(
        string $projectTitle,
        string $projectDescription
    ): array {
        $question = $this->getCompetitorQuestion();
        $insight = $this->getInsight(
            $this->getContext($projectTitle, $projectDescription),
            $question
        );
        $competitors = explode("\n", $insight);
        $competitors = array_map(function ($competitor) {
            return explode('|', $competitor);
        }, $competitors);
        $this->validateCompetitors($competitors);
        return $competitors;
    }

    private function wrapNoteQuestion(string $typeCode, string $context, bool $competitor): string
    {
        $question = $this->getNoteQuestion($typeCode, $competitor);
        $question .= ', in your answer use bullets points';
        $question .= ', use carriage return';
        $question .= ', reply in ' . $this->getLanguage($context);
        return $question;
    }

    private function getContext(string $title, string $description): string
    {
        return $title . ' - ' . $description;
    }

    private function getNoteQuestion(string $typeCode, bool $competitor = false): string
    {
        return match ($typeCode) {
            'benefits' => $competitor ? 'tell me their what are the current users benefits' : 'tell me what could be benefits for future users',
            'monetization' => $competitor ? 'tell me how they currently monetize it' : 'tell me how I could monetize it',
            'pricing' => $competitor ? 'tell me what are their current pricing plans' : 'give me possible pricing plans and features associated',
            'features' => $competitor ? 'tell me what are their current features' : 'give me possible features',
            'targets' => $competitor ? 'tell me who use their tool' : 'tell me who could be interested by my tool',
            'domains' => 'give me possible short and cool domains names',
            'competitors' => 'tell me who are the competitors for a project like this and their website if it exists',
            default => ''
        };
    }

    private function getLanguage(string $context): string
    {
        return $this->getInsight(
            $context,
            'Give me the human locale language of this project. ' .
            'Your answer should only be the human locale language like for example french, english, spanish. '.
            'Your answer should be a single word in lower case.'
        );
    }

    private function getCompetitorQuestion(): string
    {
        return 'Give me a list of project that are potential competitors for my project idea. ' .
            'Your answer must be separated by break line, without politeness phrase, no bulleted list, no numbering. ' .
            'I want the name, a brief description, and the complete website url with https://, for each competitor separated by |. ' .
            'Example of output: ' .
            'Name|Description|Url' .
            'Do not add number or bullet in front of each item.' .
            'I want only competitors with accessible website.' .
            'I want ' . self::NB_COMPETITORS . ' competitors.';
    }

    private function validateCompetitors(array &$competitors): void
    {
        foreach ($competitors as $key => $competitorData) {
            if (!isset($competitorData[0]) || !isset($competitorData[1]) || !isset($competitorData[2])) {
                \Log::info('Invalid competitor : ' . json_encode($competitors));
                unset($competitors[$key]);
                continue;
            }
            if (strlen($competitorData[0]) <= 3 || strlen($competitorData[1]) <= 3 || strlen($competitorData[2]) <= 3) {
                \Log::info('Invalid competitor : ' . json_encode($competitors));
                unset($competitors[$key]);
                continue;
            }
            $parts = parse_url($competitorData[2]);
            if (!isset($parts['scheme']) || !isset($parts['host'])) {
                \Log::info('Invalid competitor : ' . json_encode($competitors));
                unset($competitors[$key]);
                continue;
            }
        }
    }

    public function getCategoryId($title, $description): int
    {
        return (int)$this->getInsight($this->getContext($title, $description), $this->getCategoryQuestion());
    }

    private function getCategoryQuestion(): string
    {
        $existingCategories = Category::all()->toArray();
        return 'Give me the category of this project. ' .
            'Among these : ' . json_encode($existingCategories) .
            'I want only one category.' .
            'Your answer should only be the id that could be cast to int with no other text.';
    }
}