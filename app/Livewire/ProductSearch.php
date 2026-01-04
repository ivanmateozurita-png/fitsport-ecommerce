<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductSearch extends Component
{
    public $search = '';

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 2) {
            $products = Product::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('description', 'like', '%'.$this->search.'%')
                ->take(5)
                ->get();
        }

        return view('livewire.product-search', [
            'products' => $products,
        ]);
    }
}
