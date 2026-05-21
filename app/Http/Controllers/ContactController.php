<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact page with McD contact information and form.
     * Requirements: 9.3
     */
    public function index(): View
    {
        return view('contact');
    }

    /**
     * Handle contact form submission and save message to database.
     * Requirements: 9.4
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Associate with user if authenticated
        $validated['user_id'] = auth()->id();
        $validated['is_read'] = false;

        ContactMessage::create($validated);

        return redirect()->route('contact.index')
            ->with('success', 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
