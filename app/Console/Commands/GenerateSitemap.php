<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectsVisibility;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // modify this to your own needs
        \Log::info('Generating sitemap');

        $sitemap = SitemapGenerator::create(config('app.url'))->getSitemap();

        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap->add(route('app.ideas.catalog.category', ['category' => $category->code]));
            $projectIdeas = Project::where('visibility', ProjectsVisibility::where('code', 'public')->first()->id)
                ->where('category_id', Category::where('code', $category->code)->first()->id)
                ->whereNotNull('category_id')
                ->with('category')
                ->orderBy('updated_at', 'desc')
                ->get();
            foreach ($projectIdeas as $projectIdea) {
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
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        \Log::info('Sitemap Generated');
    }

    private function formatTitle(string $title): string
    {
        $title = preg_replace('/[^A-Za-z0-9]+/', '-', strtolower($title));
        return trim($title, '-');
    }
}
