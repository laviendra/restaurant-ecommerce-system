<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the cart page.
     */
    public function index(): View
    {
        $cartItems = $this->cartService->getCartItems();
        $total = $this->cartService->getTotal();

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product): JsonResponse
    {
        try {
            $request->validate([
                'quantity' => 'nullable|integer|min:1|max:99',
                'notes' => 'nullable|string|max:500',
            ]);

            // Check if product is available
            if (!$product->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is currently unavailable.',
                ], 400);
            }

            $quantity = $request->input('quantity', 1);
            $notes = $request->input('notes');
            $cartItem = $this->cartService->addItem($product, $notes, null, $quantity);
            $cartItem->load('product');

            $response = response()->json([
                'success' => true,
                'message' => 'Product added to cart.',
                'data' => [
                    'item' => [
                        'id' => $cartItem->id,
                        'product_id' => $cartItem->product_id,
                        'product_name' => $cartItem->product->name,
                        'product_price' => $cartItem->product->price,
                        'quantity' => $cartItem->quantity,
                        'notes' => $cartItem->notes,
                        'subtotal' => $cartItem->getSubtotal(),
                    ],
                    'cart_total' => $this->cartService->getTotal(),
                    'cart_count' => $this->cartService->getItemCount(),
                ],
            ]);

            // Add fresh CSRF token to response
            $response->headers->set('X-CSRF-TOKEN', csrf_token());
            
            return $response;

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the product to cart.',
            ], 500);
        }
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify the cart item belongs to the current user
        $cart = $this->cartService->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        $quantity = $request->input('quantity');
        $notes = $request->input('notes');

        $updatedItem = $this->cartService->updateQuantity($cartItem, $quantity, $notes);

        if ($updatedItem === null) {
            // Item was removed because quantity was 0
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'data' => [
                    'item' => null,
                    'removed' => true,
                    'cart_total' => $this->cartService->getTotal(),
                    'cart_count' => $this->cartService->getItemCount(),
                ],
            ]);
        }

        $updatedItem->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated.',
            'data' => [
                'item' => [
                    'id' => $updatedItem->id,
                    'product_id' => $updatedItem->product_id,
                    'product_name' => $updatedItem->product->name,
                    'product_price' => $updatedItem->product->price,
                    'quantity' => $updatedItem->quantity,
                    'notes' => $updatedItem->notes,
                    'subtotal' => $updatedItem->getSubtotal(),
                ],
                'removed' => false,
                'cart_total' => $this->cartService->getTotal(),
                'cart_count' => $this->cartService->getItemCount(),
            ],
        ]);
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(CartItem $cartItem): JsonResponse
    {
        // Verify the cart item belongs to the current user
        $cart = $this->cartService->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        $this->cartService->removeItem($cartItem);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'data' => [
                'cart_total' => $this->cartService->getTotal(),
                'cart_count' => $this->cartService->getItemCount(),
            ],
        ]);
    }

    /**
     * Get cart data as JSON (for AJAX requests).
     */
    public function getCartData(): JsonResponse
    {
        $cartItems = $this->cartService->getCartItems();
        $total = $this->cartService->getTotal();
        $count = $this->cartService->getItemCount();

        $items = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_price' => $item->product->price,
                'product_image' => $item->product->image,
                'quantity' => $item->quantity,
                'notes' => $item->notes,
                'subtotal' => $item->getSubtotal(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
                'count' => $count,
            ],
        ]);
    }
}
