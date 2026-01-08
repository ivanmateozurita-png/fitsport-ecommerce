<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Controlador del carrito de compras
 *
 * Maneja todas las operaciones relacionadas con el carrito:
 * agregar productos, actualizar cantidades, eliminar items y vaciar carrito.
 */
class CartController extends Controller
{
    /**
     * Muestra el contenido del carrito al usuario
     *
     * @return \Illuminate\View\View Vista del carrito con productos y total
     */
    public function index()
    {
        $this->normalizeCart();
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('shop.cart', compact('cart', 'total'));
    }

    /**
     * Agrega un producto al carrito de compras
     *
     * Valida stock disponible y actualiza la sesion del usuario.
     *
     * @param  Request  $request  Datos del producto (product_id, quantity, size)
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con resultado
     */
    public function add(Request $request)
    {
        $this->normalizeCart();
        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);
            $size = $request->input('size'); // Get size

            if (! $productId) {
                return $this->respuestaError('se requiere el id del producto');
            }
            $productId = $request->product_id;
            $quantity = $request->quantity;
            $size = $request->size ?? 'Única'; // Keep size for info

            $product = Product::findOrFail($productId);

            $cart = session()->get('cart', []);

            if (! $this->hayStockSuficiente($product, $quantity, $cart, $productId)) {
                return $this->respuestaError('Stock insuficiente. Solo hay '.$product->stock.' unidades disponibles.');
            }

            if (! $cart) {
                $cart = [];
            }

            // agregar item al carrito
            $cart = $this->agregarItemAlCarrito($cart, $productId, $product, $quantity, $size);

            session()->put('cart', $cart);

            return $this->respuestaExito('producto agregado al carrito exitosamente', $cart);

        } catch (\Exception $e) {
            return $this->respuestaError('error '.$e->getMessage(), 500);
        }
    }

    /**
     * Actualiza la cantidad de un producto en el carrito
     *
     * @param  Request  $request  Nueva cantidad del producto
     * @param  int  $id  ID del producto en el carrito
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
     * Elimina un producto del carrito
     *
     * @param  int  $id  ID del producto a eliminar
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
     * Vacia el carrito completo del usuario
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
     * Retorna el conteo total de items en el carrito
     *
     * @return \Illuminate\Http\JsonResponse Conteo de items
     */
    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount(),
        ]);
    }

    // funciones privadas de ayuda

    /**
     * Verifica si hay stock suficiente para agregar al carrito
     *
     * @param  Product  $product  Producto a verificar
     * @param  int  $quantity  Cantidad a agregar
     * @param  array  $cart  Carrito actual
     * @param  int  $productId  ID del producto
     * @return bool True si hay stock suficiente
     */
    private function hayStockSuficiente($product, $quantity, $cart, $productId)
    {
        $currentQty = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;

        return $product->stock >= ($currentQty + $quantity);
    }

    /**
     * Agrega o actualiza un item en el carrito
     *
     * @param  array  $cart  Carrito actual
     * @param  int  $productId  ID del producto
     * @param  Product  $product  Producto a agregar
     * @param  int  $quantity  Cantidad
     * @param  string  $size  Talla seleccionada
     * @return array Carrito actualizado
     */
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

    /**
     * Calcula el total del carrito
     *
     * @param  array  $cart  Carrito con items
     * @return float Total del carrito
     */
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

    private function respuestaExito($message, $cart = null, $extras = [])
    {
        $data = [
            'success' => true,
            'message' => $message,
            'cart_count' => $this->getCartCount(),
        ];

        if ($cart !== null) {
            $data['cart_total'] = $this->calculateTotal($cart);
        }

        return response()->json(array_merge($data, $extras));
    }

    private function respuestaError($message, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
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
