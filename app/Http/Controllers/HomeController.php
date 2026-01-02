<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Category; 

class HomeController extends Controller
{
    public function index()
    {
        // validamos si las tablas existen
        try {
            // products
            $products = Product::with('category')->take(4)->get();
            
            // categories
            $categories = Category::all();
        } catch (\Exception $e) {
            $products = collect([]);
            $categories = collect([]);
        }

        // pass data to the view
        return view('shop.home', compact('products', 'categories'));
    }
}
