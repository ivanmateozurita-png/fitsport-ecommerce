<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // busqueda
        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // filtrado por categoria
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // obtener todos los productos con paginacion
        $products = $query->paginate(9);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('shop.catalog', compact('products', 'categories'));
    }
}
