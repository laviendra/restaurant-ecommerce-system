<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of all contact messages with read status.
     * Requirements: 16.1
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::with('user');

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->input('status') === 'read') {
                $query->where('is_read', true);
            } elseif ($request->input('status') === 'unread') {
                $query->where('is_read', false);
            }
        }

        // Search by name, email, or subject
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Get unread count for display
        $unreadCount = ContactMessage::unread()->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Display the specified message detail and mark as read.
     * Requirements: 16.2
     */
    public function show(ContactMessage $message): View
    {
        // Mark message as read when viewed
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Mark a message as read.
     * Requirements: 16.2
     */
    public function markAsRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    /**
     * Get unread messages count for navigation display.
     * Requirements: 16.3
     */
    public static function getUnreadCount(): int
    {
        return ContactMessage::unread()->count();
    }
}
