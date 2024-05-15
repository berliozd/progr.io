<?php

namespace App\Services;

use App\Models\Category;
use OpenAI;
use OpenAI\Client;

class AIService
{
    const string GPT_ENGINE_VERSION_4o = 'gpt-4o';
    const string GPT_ENGINE_VERSION_35_TURBO = 'gpt-3.5-turbo';

    private const int NB_COMPETITORS = 3;

    public function getInsight(
        string $context,
        string $question,
        string $engine = self::GPT_ENGINE_VERSION_35_TURBO
    ): string {
        \Log::info($context);
        \Log::info($question);
        $client = $this->getClient();
        $response = $client->chat()->create([
            'model' => $engine,
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
        $question = $this->getNoteQuestion($noteTypeCode, $context, $competitor);
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

    public function getIdeas(string $context, string $lang): string
    {
        return $this->getInsight(
            $this->getIdeasContext($context),
            $this->getIdeasQuestion($lang),
            self::GPT_ENGINE_VERSION_4o
        );
    }

    public function getCategoryId($title, $description): int
    {
        return (int)$this->getInsight($this->getContext($title, $description), $this->getCategoryQuestion());
    }

    private function getClient(): Client
    {
        $yourApiKey = config('services.openai.api_key');
        return OpenAI::client($yourApiKey);
    }

    private function getCompetitorQuestion(): string
    {
        return 'Give me a list of project that are potential competitors for my project idea. ' .
            'Your answer must be separated by break line, without politeness phrase, no bulleted list, no numbering. ' .
            'I want the name, a brief description, and the complete website url with https://, for each competitor separated by |. ' .
            'Example of output:Name|Description|Url' .
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

    private function getIdeasContext(string $context): string
    {
        return 'You are a indie hacker, or a startuper looking for saas online project ideas that could be profitable. ' .
            'I would like you to brainstorm and give project ideas. ' .
            'The ideas should be related to ' . $context;
    }

    private function getIdeasQuestion(string $lang = 'en'): string
    {
        $lang = match ($lang) {
            'en' => 'english',
            'fr' => 'french',
            default => 'unknown',
        };
        return 'Give me 5 ideas.' .
            'Each idea must be separated by a break line.' .
            'Each idea must have a title and a description.' .
            'Title and description must be separated by |.' .
            'Do not add bullets or numbers before each ideas.' .
            'Do not and introduction text before listing teh ideas.' .
            'Do not prefix your answers with numbers.' .
            'Content should be in ' . $lang;
    }

    private function getCategoryQuestion(): string
    {
        $existingCategories = Category::all()->toArray();
        return 'Give me the category of this project. ' .
            'Among these : ' . json_encode($existingCategories) .
            'I want only one category.' .
            'Your answer should only be the id that could be cast to int with no other text.';
    }

    private function getContext(string $title, string $description): string
    {
        return $title . ' - ' . $description;
    }

    private function getNoteQuestion(string $typeCode, string $context, bool $competitor): string
    {
        $question = match ($typeCode) {
            'benefits' => $competitor ? 'tell me their what are the current users benefits (maximum 5 benefits)' : 'tell me what could be benefits for future users (maximum 5 benefits)',
            'monetization' => $competitor ? 'tell me how they currently monetize it (maximum 5 ways)' : 'tell me how I could monetize it (maximum 5 ways)',
            'pricing' => $competitor ? 'tell me what are their current pricing plans' : 'give me possible pricing plans and features associated (maximum 3 plans)',
            'features' => $competitor ? 'tell me what are their current features (maximum 7 features)' : 'give me possible features (maximum 7 features)',
            'targets' => $competitor ? 'tell me who use their tool (maximum 7 types)' : 'tell me who could be interested by my tool (maximum 7 types of users)',
            'domains' => 'give me possible short and cool domains names (maximum 7) that are not already taken',
            'competitors' => 'tell me who are the competitors for a project like this and their website if it exists',
            default => ''
        };
        $question .= ', in your answer use bullets points';
        $question .= ', use carriage return';
        $question .= ', reply in ' . $this->getLanguageFromContext($context);
        $question .= ', do not start your answer with a introduction sentence like "Certainly! ..." or "Sure! ..."';
        $question .= ', just give your answer without introducing it.';
        return $question;
    }

    private function getLanguageFromContext(string $context): string
    {
        return $this->getInsight(
            $context,
            'Give me the human locale language of this project. ' .
            'Your answer should only be the human locale language like for example french, english, spanish. ' .
            'Your answer should be a single word in lower case.'
        );
    }
}