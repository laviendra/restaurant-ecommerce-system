<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get or create cart for the authenticated user.
     */
    public function getCart(?User $user = null): Cart
    {
        $user = $user ?? Auth::user();
        
        if (!$user) {
            throw new \RuntimeException('User must be authenticated to access cart');
        }

        return Cart::firstOrCreate(
            ['user_id' => $user->id]
        );
    }

    /**
     * Add an item to the cart.
     * If the item already exists, increment the quantity by the specified amount.
     *
     * @param Product $product The product to add
     * @param string|null $notes Optional notes for special requests
     * @param User|null $user The user (defaults to authenticated user)
     * @param int $quantity The quantity to add (defaults to 1)
     * @return CartItem The created or updated cart item
     */
    public function addItem(Product $product, ?string $notes = null, ?User $user = null, int $quantity = 1): CartItem
    {
        $cart = $this->getCart($user);

        // Check if item already exists in cart
        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            // Increment quantity by the specified amount
            $existingItem->quantity += $quantity;
            if ($notes !== null) {
                $existingItem->notes = $notes;
            }
            $existingItem->save();
            return $existingItem;
        }

        // Create new cart item with specified quantity
        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'notes' => $notes,
        ]);
    }

    /**
     * Update the quantity of a cart item.
     * If quantity is zero or less, remove the item.
     *
     * @param CartItem $cartItem The cart item to update
     * @param int $quantity The new quantity
     * @param string|null $notes Optional notes update
     * @return CartItem|null The updated cart item, or null if removed
     */
    public function updateQuantity(CartItem $cartItem, int $quantity, ?string $notes = null): ?CartItem
    {
        if ($quantity <= 0) {
            $this->removeItem($cartItem);
            return null;
        }

        $cartItem->quantity = $quantity;
        if ($notes !== null) {
            $cartItem->notes = $notes;
        }
        $cartItem->save();

        return $cartItem;
    }

    /**
     * Update notes for a cart item.
     *
     * @param CartItem $cartItem The cart item to update
     * @param string|null $notes The new notes
     * @return CartItem The updated cart item
     */
    public function updateNotes(CartItem $cartItem, ?string $notes): CartItem
    {
        $cartItem->notes = $notes;
        $cartItem->save();

        return $cartItem;
    }

    /**
     * Remove an item from the cart.
     *
     * @param CartItem $cartItem The cart item to remove
     * @return bool True if removed successfully
     */
    public function removeItem(CartItem $cartItem): bool
    {
        return $cartItem->delete();
    }

    /**
     * Clear all items from the cart.
     *
     * @param User|null $user The user (defaults to authenticated user)
     * @return bool True if cleared successfully
     */
    public function clearCart(?User $user = null): bool
    {
        $cart = $this->getCart($user);
        return $cart->items()->delete() >= 0;
    }

    /**
     * Get the total amount of the cart.
     *
     * @param User|null $user The user (defaults to authenticated user)
     * @return float The total amount
     */
    public function getTotal(?User $user = null): float
    {
        $cart = $this->getCart($user);
        $cart->load('items.product');
        
        return $cart->getTotal();
    }

    /**
     * Get cart items with product details.
     *
     * @param User|null $user The user (defaults to authenticated user)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCartItems(?User $user = null)
    {
        $cart = $this->getCart($user);
        return $cart->items()->with('product')->get();
    }

    /**
     * Get the count of items in the cart.
     *
     * @param User|null $user The user (defaults to authenticated user)
     * @return int The number of items
     */
    public function getItemCount(?User $user = null): int
    {
        $cart = $this->getCart($user);
        return $cart->items()->sum('quantity');
    }
}
