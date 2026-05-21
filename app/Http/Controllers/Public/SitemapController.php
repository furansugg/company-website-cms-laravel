<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(url('/'))->setPriority(1.0))
            ->add(Url::create(url('/services'))->setPriority(0.8))
            ->add(Url::create(url('/blog'))->setPriority(0.8))
            ->add(Url::create(url('/contact'))->setPriority(0.5));

        Page::published()->each(function (Page $page) use ($sitemap) {
            $sitemap->add(
                Url::create(url("/pages/{$page->slug}"))
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.6)
            );
        });

        Article::published()->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create(url("/blog/{$article->slug}"))
                    ->setLastModificationDate($article->updated_at)
                    ->setPriority(0.7)
            );
        });

        Service::active()->each(function (Service $service) use ($sitemap) {
            $sitemap->add(
                Url::create(url("/services/{$service->slug}"))
                    ->setLastModificationDate($service->updated_at)
                    ->setPriority(0.6)
            );
        });

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
