<?php

namespace App\Services;

class SeoService
{
    public function getDescription(string $categoryCode = null, array $categories = null): string
    {
        $description = $this->getCatalogMetaDescription();
        if (!empty($categoryCode)) {
            $description .= ' Project ideas for category : ' . trans('app.ideas.catalog.category.' . $categoryCode);
            return $description;
        }
        if (!empty($categories)) {
            $concatenatedCats = array_map(function ($category) {
                return $category->code;
            }, $categories);
            $concatenatedCats = implode(', ', $concatenatedCats);
            $description .= ' Project ideas for all the following categories : ' . $concatenatedCats;
            return $description;
        }
        return $description;
    }

    public function getKeywords(string $categoryCode = null): string
    {
        $keywords = $this->getCatalogMetaKeywords();
        if (!empty($categoryCode)) {
            $keywords .= ', ' . trans('app.ideas.catalog.category.' . $categoryCode);
        }
        return $keywords;
    }


    private function getCatalogMetaDescription(): string
    {
        return 'Discover a wide range of innovative and creative project ideas for startups, entrepreneurs, indie hackers, and makers. '
            . 'From web development and mobile apps to AI and e-commerce, our project ideas are designed to inspire and motivate you to build something amazing. '
            . 'Explore our list of project ideas today and start turning your ideas into reality!';
    }

    private function getCatalogMetaKeywords(): string
    {
        return 'Project ideas, Startup ideas, Business ideas, Innovative ideas, Creative ideas, Tech project ideas, Web development project ideas, '
            . 'Mobile app project ideas, AI project ideas, E-commerce project ideas, Side project ideas, Indie hacker project ideas, Entrepreneur project ideas, '
            . 'Maker project ideas, Hobby project ideas, Open source project ideas, Collaborative project ideas';
    }

    public function getGenericKeywords(): string
    {
        return 'SaaS tool builder,Idea management,Idea tracking,Idea refinement,AI integration,Project planning,Collaboration tool,Productivity tool,Software development,' .
            'Web development,Cloud-based tool,SaaS platform,SaaS application,Startup ideas,Business ideas,Innovation management';
    }

    public function getGenericDescription(): string
    {
        return 'Designed to help SaaS tool builders track, refine, and bring their ideas to life. '
            . 'Add details about features for monetization, targeting, and benefits, as well as intelligent suggestions and insights from AI';
    }
}