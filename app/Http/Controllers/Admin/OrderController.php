<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'estado del pedido actualizado correctamente');
    }

    /**
     * elimina el pedido de la base de datos
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'pedido eliminado correctamente');
    }
}
