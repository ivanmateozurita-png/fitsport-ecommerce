<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * muestra la lista de productos
     */
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * muestra el formulario para crear un nuevo producto
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * almacena un producto recien creado en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/uploads/products'), $filename);
            $data['image_path'] = 'assets/uploads/products/' . $filename;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'producto creado exitosamente');
    }

    /**
     * muestra el formulario para editar un producto
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * actualiza el producto especificado en la base de datos
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image_path && strpos($product->image_path, 'assets/uploads') !== false) {
                 $oldPath = public_path($product->image_path);
                 if (file_exists($oldPath)) {
                     @unlink($oldPath);
                 }
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/uploads/products'), $filename);
            $data['image_path'] = 'assets/uploads/products/' . $filename;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'producto actualizado exitosamente');
    }

    /**
     * elimina el producto especificado de la base de datos
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image_path && strpos($product->image_path, 'assets/uploads') !== false) {
             $oldPath = public_path($product->image_path);
             if (file_exists($oldPath)) {
                 @unlink($oldPath);
             }
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'producto eliminado exitosamente');
    }
}
