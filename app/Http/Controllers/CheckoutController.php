<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Display the checkout page with order summary.
     */
    public function index(): View|RedirectResponse
    {
        $cartItems = $this->cartService->getCartItems();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        $total = $this->cartService->getTotal();

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process the checkout form and create order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,transfer_bank',
        ]);

        $cartItems = $this->cartService->getCartItems();
        
        // Verify cart is not empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Store checkout data in session for payment processing
        session([
            'checkout_data' => [
                'delivery_address' => $validated['delivery_address'],
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
            ]
        ]);

        // If COD, create order immediately
        if ($validated['payment_method'] === 'cod') {
            $order = $this->orderService->createOrder(
                auth()->user(),
                $validated['delivery_address'],
                $validated['notes'] ?? null,
                'cod'
            );

            return redirect()->route('checkout.confirmation', $order)
                ->with('success', 'Order placed successfully!');
        }

        // For transfer bank, redirect to payment page
        return redirect()->route('payment.index');
    }

    /**
     * Display order confirmation page.
     */
    public function confirmation($order): View
    {
        // Load order with relationships
        $order = \App\Models\Order::with(['items', 'user', 'invoice'])
            ->where('user_id', auth()->id())
            ->findOrFail($order);

        return view('checkout.confirmation', compact('order'));
    }
}
