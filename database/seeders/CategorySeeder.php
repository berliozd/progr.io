<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sequences = [
            [
                'code' => 'productivity',
                'description' => 'Tools and systems to improve productivity and efficiency.'
            ],
            [
                'code' => 'virtual-assistant',
                'description' => 'Tools providing remote administrative support to individuals or businesses such as scheduling appointments, managing emails, data entry.'
            ],
            [
                'code' => 'project-management',
                'description' => 'Tools and systems to help individuals and teams manage projects more effectively such as project planning software, task management apps, or project management coaching services. '
            ],
            [
                'code' => 'training',
                'description' => 'Tools related to online training and development programs for individuals and organizations such as e-learning courses, webinars, and virtual workshops.'
            ],
            [
                'code' => 'travel',
                'description' => 'Tools and services to help individuals plan and book travel online such as travel itinerary planning, hotel and flight booking, and virtual travel experiences.'
            ],
            [
                'code' => 'marketing',
                'description' => 'Tools and services to help businesses and organizations reach and engage with their target audience such as email marketing, content marketing, and search engine optimization.'
            ],
            [
                'code' => 'social-media',
                'description' => 'Tools and services to help businesses and organizations build and maintain their social media presence such as content creation, social media advertising, community management, and analytics reporting.'
            ],
            [
                'code' => 'e-commerce',
                'description' => 'Tools for creating and managing online stores to help businesses and organizations sell their products and services online such as website design, product catalog management, payment processing, and order fulfillment.'
            ],
            [
                'code' => 'video',
                'description' => 'Tools or services to help producing high-quality video content for businesses and organizations such as promotional videos, explainer videos, product demos, and social media videos.'
            ],
            [
                'code' => 'podcast',
                'description' => 'Tools or services to help producing high-quality audio content for businesses and organizations such as podcast series, single episodes, and audio ads.'
            ],
            [
                'code' => 'blogging',
                'description' => 'Tools or services to help managing blogs for businesses and organizations such as content creation, search engine optimization, social media promotion, and analytics reporting.'
            ],
            [
                'code' => 'house-decoration',
                'description' => 'Tools or services to help individuals and businesses create beautiful and functional living spaces such as interior design, furniture selection, color consulting, and space planning.'
            ],
            [
                'code' => 'real-estate',
                'description' => 'Tools or services to help individuals and businesses buy, sell, or rent properties such as property listing, market analysis, buyer/seller representation, and property management.'
            ],
            [
                'code' => 'fitness',
                'description' => 'Tools or services to help individuals and businesses improve their physical health and well-being such as personal training, group fitness classes, nutrition coaching, and wellness programs.'
            ],
            [
                'code' => 'art',
                'description' => 'Tools or services to help individuals and businesses create and appreciate visual art such as art creation, art instruction, art curation, and art appraisal.'
            ],
            [
                'code' => 'culture',
                'description' => 'Tools or services to help individuals and businesses learn about and appreciate different cultures such as language instruction, cultural events, cultural tours, and diversity training.'
            ],
            [
                'code' => 'books',
                'description' => 'Tools or services related to books, reading, writing, publishing, and marketing.'
            ],
            [
                'code' => 'music',
                'description' => 'Tools or services related to music distribution, listening, and streaming.'
            ],
            [
                'code' => 'movies',
                'description' => 'Tools or services related to movies, cinema, and TV.'
            ],
            [
                'code' => 'games',
                'description' => 'Tools or services related to video games.'
            ],
            [
                'code' => 'events',
                'description' => 'Tools or services to help planning and executing online events, such as webinars, conferences, and festivals.'
            ],
            [
                'code' => 'food',
                'description' => 'Tools or services to help individuals and businesses create, prepare, distribute, and enjoy food.'
            ],
            [
                'code' => 'sport',
                'description' => 'Tools or services to help individuals and businesses engage in, promote sports, improve their performance, and follow their sports.'
            ],
            [
                'code' => 'positiveness',
                'description' => 'Tools or services related to positivity and well-being.'
            ],
            [
                'code' => 'growth',
                'description' => 'Tools or services that help individuals and businesses achieve growth in various areas.'
            ]
        ];
        foreach ($sequences as $sequence) {
            Category::factory()->create($sequence);
        }
    }
}
