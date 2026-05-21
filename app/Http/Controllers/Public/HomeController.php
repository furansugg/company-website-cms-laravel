<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('public.home', [
            'banners' => Banner::active()->orderBy('sort_order')->get(),
            'services' => Service::active()->orderBy('sort_order')->limit(6)->get(),
            'articles' => Article::published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
