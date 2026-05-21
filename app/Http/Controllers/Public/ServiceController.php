<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('public.services.index', [
            'services' => Service::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        return view('public.services.show', ['service' => $service]);
    }
}
