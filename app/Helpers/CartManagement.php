<?php

namespace App\Helpers;

class CartManagement
{
    const COOKIE_NAME = 'cart_items';
    const COOKIE_MINUTES = 60 * 24 * 7; // 1 week

    // Add item to cart
    public static function addItem($productId, $quantity = 1)
    {
        $cart = self::getCartItems();
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = ['product_id' => $productId, 'quantity' => $quantity];
        }
        self::setCartItems($cart);
    }

    // Remove item from cart
    public static function removeItem($productId)
    {
        $cart = self::getCartItems();
        $cart = array_filter($cart, function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        });
        self::setCartItems(array_values($cart));
    }

    // Add cart items to cookie
    public static function setCartItems($cart)
    {
        cookie()->queue(cookie(self::COOKIE_NAME, json_encode($cart), self::COOKIE_MINUTES));
    }

    // Clear cart items from cookie
    public static function clearCart()
    {
        cookie()->queue(cookie()->forget(self::COOKIE_NAME));
    }

    // Get all cart items from cookie
    public static function getCartItems()
    {
        $cart = request()->cookie(self::COOKIE_NAME);
        return $cart ? json_decode($cart, true) : [];
    }

    // Increment item quantity
    public static function incrementItem($productId)
    {
        $cart = self::getCartItems();
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity']++;
                break;
            }
        }
        self::setCartItems($cart);
    }

    // Decrement item quantity
    public static function decrementItem($productId)
    {
        $cart = self::getCartItems();
        foreach ($cart as $key => &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity']--;
                if ($item['quantity'] <= 0) {
                    unset($cart[$key]);
                }
                break;
            }
        }
        self::setCartItems(array_values($cart));
    }

    // Calculate grand total
    public static function calculateGrandTotal()
    {
        $cart = self::getCartItems();
        $total = 0;
        foreach ($cart as $item) {
            $product = \App\Models\Shop\Product::find($item['product_id']);
            if ($product) {
                $total += $product->price * $item['quantity'];
            }
        }
        return $total;
    }
}
