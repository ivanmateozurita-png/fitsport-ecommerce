<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    /**
     * muestra el panel de administracion dashoard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // obtenemos el conteo total de productos
        $productCount = Product::count();

        // obtenemos el conteo total de pedidos
        $ordersCount = Order::count();

        // obtenemos los 5 pedidos mas recientes con su usuario relacionado
        $recentOrders = Order::with('user')->orderBy('date', 'desc')->take(5)->get();

        // contamos productos con stock bajo menos de 10 unidades
        $lowStock = Product::where('stock', '<', 10)->count();

        // retornamos la vista del dashboard con los datos
        return view('admin.dashboard', compact('productCount', 'ordersCount', 'recentOrders', 'lowStock'));
    }
}
