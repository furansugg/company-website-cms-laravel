<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::create([
            ...$request->validated(),
            'status' => ContactMessage::STATUS_UNREAD,
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('contact')
            ->with('success', 'Thanks for reaching out! We will get back to you soon.');
    }
}
