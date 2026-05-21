<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::firstOrCreate([], [
            'site_name' => 'Company Website CMS',
            'site_description' => 'Demo CMS built with Laravel 12 + Filament',
            'primary_color' => '#2563eb',
            'default_meta_title' => 'Company Website CMS',
            'default_meta_description' => 'A modern, lightweight CMS for company websites.',
        ]);

        CompanyProfile::firstOrCreate([], [
            'name' => 'Acme Corporation',
            'about' => 'Acme Corporation is a sample company used to demonstrate this CMS.',
            'vision' => 'To be a leading example of CMS-driven company websites.',
            'mission' => 'Deliver reliable, fast and easy-to-manage company websites.',
            'email' => 'hello@acme.test',
            'phone' => '+62 812 3456 7890',
            'address' => 'Jl. Sudirman No. 1, Jakarta, Indonesia',
            'social_media' => [
                'twitter' => 'https://twitter.com/acme',
                'linkedin' => 'https://linkedin.com/company/acme',
                'instagram' => 'https://instagram.com/acme',
            ],
        ]);

        $author = User::where('email', 'admin@example.com')->first();

        $pages = [
            ['title' => 'About Us', 'content' => 'We are a sample company demonstrating the CMS.'],
            ['title' => 'Privacy Policy', 'content' => 'Sample privacy policy content.'],
            ['title' => 'Terms of Service', 'content' => 'Sample terms of service content.'],
        ];
        foreach ($pages as $index => $page) {
            Page::firstOrCreate(
                ['slug' => str($page['title'])->slug()->value()],
                [
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'excerpt' => str($page['content'])->limit(120),
                    'status' => Page::STATUS_PUBLISHED,
                    'published_at' => now(),
                    'sort_order' => $index,
                    'user_id' => $author?->id,
                ]
            );
        }

        $news = Category::firstOrCreate(
            ['slug' => 'news'],
            ['name' => 'News', 'description' => 'Latest news.']
        );
        Category::firstOrCreate(
            ['slug' => 'tutorials'],
            ['name' => 'Tutorials', 'description' => 'How-to articles.']
        );

        $laravelTag = Tag::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel']);
        $filamentTag = Tag::firstOrCreate(['slug' => 'filament'], ['name' => 'Filament']);

        foreach (range(1, 3) as $i) {
            $article = Article::firstOrCreate(
                ['slug' => "sample-article-{$i}"],
                [
                    'title' => "Sample article {$i}",
                    'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'content' => str_repeat("Sample paragraph for article {$i}.\n\n", 5),
                    'status' => Article::STATUS_PUBLISHED,
                    'published_at' => now()->subDays($i),
                    'category_id' => $news->id,
                    'user_id' => $author?->id,
                ]
            );
            $article->tags()->syncWithoutDetaching([$laravelTag->id, $filamentTag->id]);
        }

        $services = [
            ['name' => 'Web Development', 'description' => 'Custom websites and web apps.', 'icon' => 'heroicon-o-code-bracket'],
            ['name' => 'Mobile Apps', 'description' => 'iOS and Android mobile applications.', 'icon' => 'heroicon-o-device-phone-mobile'],
            ['name' => 'Cloud Hosting', 'description' => 'Reliable hosting and DevOps.', 'icon' => 'heroicon-o-cloud'],
        ];
        foreach ($services as $i => $service) {
            Service::firstOrCreate(
                ['slug' => str($service['name'])->slug()->value()],
                array_merge($service, [
                    'is_active' => true,
                    'sort_order' => $i,
                ])
            );
        }

        Banner::firstOrCreate(
            ['title' => 'Welcome to Acme'],
            [
                'subtitle' => 'A modern CMS demo built with Laravel 12 and Filament.',
                'cta_label' => 'Explore Services',
                'cta_url' => '/services',
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        $header = [
            ['label' => 'Home', 'url' => '/', 'sort_order' => 0],
            ['label' => 'About', 'url' => '/about-us', 'sort_order' => 1],
            ['label' => 'Services', 'url' => '/services', 'sort_order' => 2],
            ['label' => 'Blog', 'url' => '/blog', 'sort_order' => 3],
            ['label' => 'Contact', 'url' => '/contact', 'sort_order' => 4],
        ];
        foreach ($header as $item) {
            Menu::firstOrCreate(
                ['location' => Menu::LOCATION_HEADER, 'label' => $item['label']],
                array_merge($item, ['location' => Menu::LOCATION_HEADER, 'is_active' => true])
            );
        }

        $footer = [
            ['label' => 'Privacy Policy', 'url' => '/pages/privacy-policy', 'sort_order' => 0],
            ['label' => 'Terms of Service', 'url' => '/pages/terms-of-service', 'sort_order' => 1],
        ];
        foreach ($footer as $item) {
            Menu::firstOrCreate(
                ['location' => Menu::LOCATION_FOOTER, 'label' => $item['label']],
                array_merge($item, ['location' => Menu::LOCATION_FOOTER, 'is_active' => true])
            );
        }
    }
}
