<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectsVisibility;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        \Log::info('Generating sitemap');
        $sitemap = SitemapGenerator::create(config('app.url'))->getSitemap();
        $this->addCategories($sitemap);
        $sitemap->writeToFile(public_path('sitemap.xml'));
        \Log::info('Sitemap Generated');
    }

    private function formatTitle(string $title): string
    {
        $title = preg_replace('/[^A-Za-z0-9]+/', '-', strtolower($title));
        return trim($title, '-');
    }

    public function addCategories(Sitemap $sitemap): void
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap->add(route('app.ideas.catalog.category', ['category' => $category->code]));
            $this->addCategoryProjectIdeas($sitemap, $category);
        }
    }

    public function addProjectIdea(Sitemap $sitemap, Category $category, mixed $projectIdea): void
    {
        \Log::debug($projectIdea->title);
        \Log::debug($this->formatTitle($projectIdea->title));
        $sitemap->add(
            route(
                'app.ideas.catalog.idea',
                [
                    'category' => $category->code,
                    'title' => $this->formatTitle($projectIdea->title),
                    'id' => $projectIdea->id
                ]
            )
        );
    }

    public function addCategoryProjectIdeas(Sitemap $sitemap, Category $category): void
    {
        $projectIdeas = Project::where('visibility', ProjectsVisibility::where('code', 'public')->first()->id)
            ->where('category_id', Category::where('code', $category->code)->first()->id)
            ->whereNotNull('category_id')
            ->with('category')
            ->orderBy('updated_at', 'desc')
            ->get();
        foreach ($projectIdeas as $projectIdea) {
            $this->addProjectIdea($sitemap, $category, $projectIdea);
        }
    }
}
