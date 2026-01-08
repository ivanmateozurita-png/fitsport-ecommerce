<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * muestra la lista de todos los pedidos
     */
    public function index()
    {
        // obtengo los pedidos ordenados por fecha descendente paginados
        $orders = Order::with('user')->orderBy('date', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * muestra el detalle de un pedido especifico
     */
    public function show($id)
    {
        // cargo el pedido con su usuario y los items productos
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * actualiza el estado del pedido
     * Si se cancela, restaura el stock de los productos
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
        ]);

        $order = Order::with('items')->findOrFail($id);
        $previousStatus = $order->status;
        $newStatus = $request->status;

        // Si se cancela y antes no estaba cancelado, restaurar stock
        if ($newStatus === 'cancelled' && $previousStatus !== 'cancelled') {
            $this->restoreStock($order);
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->back()->with('success', 'estado del pedido actualizado correctamente');
    }

    /**
     * elimina el pedido de la base de datos
     * Si el pedido no estaba cancelado, restaura el stock primero
     */
    public function destroy($id)
    {
        $order = Order::with('items')->findOrFail($id);

        // Si el pedido no estaba cancelado, restaurar stock antes de eliminar
        if ($order->status !== 'cancelled') {
            $this->restoreStock($order);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'pedido eliminado correctamente');
    }

    /**
     * Restaura el stock de todos los productos de un pedido
     *
     * @param  Order  $order  Pedido con items cargados
     * @return void
     */
    private function restoreStock(Order $order)
    {
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock += $item->quantity;
                $product->save();
            }
        }
    }
}
