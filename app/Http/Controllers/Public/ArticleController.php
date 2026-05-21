<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::published()
            ->with(['category', 'author', 'tags'])
            ->when($request->string('category')->isNotEmpty(), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
            })
            ->when($request->string('tag')->isNotEmpty(), function ($query) use ($request) {
                $query->whereHas('tags', fn ($q) => $q->where('slug', $request->string('tag')));
            })
            ->when($request->string('q')->isNotEmpty(), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%'.$request->string('q').'%')
                        ->orWhere('excerpt', 'like', '%'.$request->string('q').'%');
                });
            })
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.articles.index', [
            'articles' => $articles,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->limit(20)->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $article = Article::published()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views');

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('public.articles.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
