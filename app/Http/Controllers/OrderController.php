<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * muestra la pagina de pago checkout
     */
    public function checkout()
    {
        // verificar si el carrito esta vacio
        $cart = session()->get('cart', []);
        
        if (count($cart) <= 0) {
            return redirect()->route('cart.index')->with('error', 'tu carrito esta vacio');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Calcular IVA 15%
        $iva = $subtotal * 0.15;
        $total = $subtotal + $iva;

        return view('shop.checkout', compact('cart', 'subtotal', 'iva', 'total'));
    }

    /**
     * procesa el pedido 
     */
    public function process(Request $request)
    {
        // validar datos de envio
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'tu carrito esta vacio');
        }

        try {
            DB::beginTransaction();

            // calcular subtotal
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                
                // validar producto y stock
                $product = \App\Models\Product::find($item['id']);
                if (!$product) {
                    throw new \Exception("el producto {$item['name']} ya no existe");
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("stock insuficiente para {$item['name']} solo quedan {$product->stock} unidades");
                }
            }
            
            // Calcular IVA 15% y total
            $iva = $subtotal * 0.15;
            $total = $subtotal + $iva;

            // crear pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pending', // por defecto pendiente hasta pago real
                'date' => now()
            ]);

            // crear items y actualizar stock
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'size' => $item['size'] ?? null
                ]);

                // descontar stock
                $product = \App\Models\Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }

            // vaciar carrito
            session()->forget('cart');

            DB::commit();

            return redirect()->route('order.confirmation', $order->id)
                           ->with('success', 'pedido realizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'error al procesar el pedido ' . $e->getMessage());
        }
    }

    /**
     * muestra el historial de pedidos
     */
    public function myOrders()
    {
        // obtener pedidos del usuario autenticado ordenados por fecha
        $orders = Order::where('user_id', Auth::id())
                       ->orderBy('date', 'desc')
                       ->with('items') // cargar items para conteo
                       ->get();

        return view('shop.my-orders', compact('orders'));
    }

    /**
     * muestra la confirmacion detalle de un pedido
     */
    public function confirmation($id)
    {
        // buscar pedido y verificar que pertenezca al usuario
        $order = Order::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->with('items.product') // cargar relaciones
                      ->firstOrFail();

        return view('shop.order-confirmation', compact('order'));
    }
}
