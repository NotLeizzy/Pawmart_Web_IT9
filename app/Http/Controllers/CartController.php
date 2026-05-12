<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function updateQuantity(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $quantity = $validated['quantity'];
            $stock = $cart[$productId]['stock'];

            // Don't allow quantity to exceed stock
            if ($quantity > $stock) {
                $quantity = $stock;
            }

            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully.',
                'quantity' => $quantity,
                'subtotal' => $cart[$productId]['price'] * $quantity,
                'total' => $this->calculateTotal($cart),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.',
        ], 404);
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart.',
                'total' => $this->calculateTotal($cart),
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.',
        ], 404);
    }

    /**
     * Calculate the total price of all items in the cart.
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
