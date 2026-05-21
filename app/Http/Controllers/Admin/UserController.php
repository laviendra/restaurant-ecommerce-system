<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of all registered users with pagination.
     * Requirements: 13.1
     */
    public function index(Request $request): View
    {
        $query = User::withCount('orders')
            ->with(['orders' => function ($q) {
                $q->select('id', 'user_id', 'total_amount');
            }]);

        // Search by name, email, or phone
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Calculate total orders for each user
        $users->getCollection()->transform(function ($user) {
            $user->total_spent = $user->orders->sum('total_amount');
            return $user;
        });

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user detail with order history.
     * Requirements: 13.2
     */
    public function show(User $user): View
    {
        $user->load(['orders' => function ($query) {
            $query->with('items')->orderBy('created_at', 'desc');
        }]);

        // Calculate user statistics
        $stats = [
            'total_orders' => $user->orders->count(),
            'total_spent' => $user->orders->sum('total_amount'),
            'completed_orders' => $user->orders->where('order_status', 'completed')->count(),
            'pending_orders' => $user->orders->where('order_status', 'pending')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }
}
