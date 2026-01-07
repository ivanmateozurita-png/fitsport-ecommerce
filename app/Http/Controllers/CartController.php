<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * muestra el contenido del carrito
     */
    public function index()
    {
        $this->normalizeCart();
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('shop.cart', compact('cart', 'total'));
    }

    /**
     * agrega un producto al carrito
     */
    public function add(Request $request)
    {
        $this->normalizeCart();
        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);
            $size = $request->input('size'); // Get size

            if (! $productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'se requiere el id del producto',
                ], 400);
            }
            $productId = $request->product_id;
            $quantity = $request->quantity;
            $size = $request->size ?? 'Única'; // Keep size for info

            $product = Product::findOrFail($productId);

            $cart = session()->get('cart', []);

            if (! $this->hayStockSuficiente($product, $quantity, $cart, $productId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Solo hay '.$product->stock.' unidades disponibles.',
                ], 400);
            }

            if (! $cart) {
                $cart = [];
            }

            // agregar item al carrito
            $cart = $this->agregarItemAlCarrito($cart, $productId, $product, $quantity, $size);

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'producto agregado al carrito exitosamente',
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->calculateTotal($cart),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'error '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * actualiza la cantidad de un producto
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($cart[$id]['id']);

            // validar stock
            if ($product && $product->stock < $request->quantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "stock insuficiente solo quedan {$product->stock} unidades",
                    ], 400);
                }

                return redirect()->back()->with('error', 'stock insuficiente');
            }

            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'cantidad actualizada',
                    'cart_count' => $this->getCartCount(),
                    'cart_total' => $this->calculateTotal($cart),
                    'item_subtotal' => $cart[$id]['price'] * $cart[$id]['quantity'],
                ]);
            }
        }

        return redirect()->back();
    }

    /**
     * elimina un producto del carrito
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'producto eliminado',
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->calculateTotal($cart),
            ]);
        }

        return redirect()->back()->with('success', 'producto eliminado');
    }

    /**
     * vacia el carrito completo
     */
    public function clear()
    {
        session()->forget('cart');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'carrito vaciado',
            ]);
        }

        return redirect()->back()->with('success', 'carrito vaciado');
    }

    /**
     * retorna el conteo de items (para ajax)
     */
    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount(),
        ]);
    }

    // funciones privadas de ayuda

    private function hayStockSuficiente($product, $quantity, $cart, $productId)
    {
        $currentQty = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;

        return $product->stock >= ($currentQty + $quantity);
    }

    private function agregarItemAlCarrito($cart, $productId, $product, $quantity, $size)
    {
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image_path' => $product->image_path,
                'size' => $size,
            ];
        }

        return $cart;
    }

    private function getCartCount()
    {
        $this->normalizeCart();
        $cart = session()->get('cart', []);

        return array_sum(array_column($cart, 'quantity'));
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['quantity'] ?? 0;
            $total += $price * $qty;
        }

        return $total;
    }

    /**
     * normaliza el carrito
     */
    private function normalizeCart()
    {
        $cart = session()->get('cart', []);
        $updated = false;

        foreach ($cart as $key => $item) {
            if (! isset($item['price']) && isset($item['precio'])) {
                $cart[$key]['price'] = $item['precio'];
                $cart[$key]['name'] = $item['nombre'];
                $cart[$key]['quantity'] = $item['cantidad'];
                $cart[$key]['image_path'] = $item['imagen_url'] ?? '';
                $updated = true;
            }
            if (! isset($item['id'])) {
                $cart[$key]['id'] = $item['id_producto'] ?? $key;
                $updated = true;
            }
        }

        if ($updated) {
            session()->put('cart', $cart);
        }
    }
}
