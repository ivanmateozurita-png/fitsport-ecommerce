<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * muestra el reporte de ventas filtrado por fecha
     */
    public function sales(Request $request)
    {
        $query = \App\Models\Order::with('user')->whereIn('status', ['paid', 'shipped', 'delivered']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $orders = $query->orderBy('date', 'desc')->get();
        $totalRevenue = $orders->sum('total');

        return view('admin.reports.sales', compact('orders', 'totalRevenue'));
    }
}
