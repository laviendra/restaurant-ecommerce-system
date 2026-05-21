<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Display the payment page for Transfer Bank.
     */
    public function index(): View|RedirectResponse
    {
        // Check if checkout data exists in session
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData || $checkoutData['payment_method'] !== 'transfer_bank') {
            return redirect()->route('checkout.index')
                ->with('error', 'Please complete checkout first.');
        }

        $cartItems = $this->cartService->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $total = $this->cartService->getTotal();

        // Bank details for transfer
        $bankDetails = [
            'bank_name' => 'Bank Central Asia (BCA)',
            'account_number' => '1234567890',
            'account_name' => 'McDonald\'s Indonesia',
        ];

        return view('payment.index', compact('cartItems', 'total', 'checkoutData', 'bankDetails'));
    }

    /**
     * Process payment proof upload and create order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        // Check if checkout data exists in session
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('checkout.index')
                ->with('error', 'Please complete checkout first.');
        }

        $cartItems = $this->cartService->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Store payment proof image
        $paymentProofPath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        // Create order with payment proof
        $order = $this->orderService->createOrder(
            auth()->user(),
            $checkoutData['delivery_address'],
            $checkoutData['notes'] ?? null,
            'transfer_bank',
            $paymentProofPath
        );

        // Clear checkout data from session
        session()->forget('checkout_data');

        return redirect()->route('checkout.confirmation', $order)
            ->with('success', 'Order placed successfully! We will verify your payment.');
    }

    /**
     * Handle COD payment creation (alternative endpoint).
     */
    public function processCod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = $this->cartService->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $order = $this->orderService->createOrder(
            auth()->user(),
            $validated['delivery_address'],
            $validated['notes'] ?? null,
            'cod'
        );

        return redirect()->route('checkout.confirmation', $order)
            ->with('success', 'Order placed successfully!');
    }
}
