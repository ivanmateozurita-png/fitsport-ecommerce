<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        // encontrar producto por id o fallar con 404
        $product = Product::findOrFail($id);

        // retornar la vista de detalle
        return view('shop.product', compact('product'));
    }
}
