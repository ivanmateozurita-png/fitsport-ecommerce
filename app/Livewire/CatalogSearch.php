<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogSearch extends Component
{
    use WithPagination;

    public $search = '';

    public $categoryId = null;

    public $sortBy = 'default';

    protected $paginationTheme = 'bootstrap';

    protected $queryString = ['search' => ['as' => 'q'], 'categoryId' => ['as' => 'category_id']];

    public function mount()
    {
        $this->search = request('q', '');
        $this->categoryId = request('category_id');
    }

    public function render()
    {
        $query = Product::with('category');

        // Búsqueda en tiempo real
        if (strlen($this->search) >= 2) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Filtro por categoría (incluye subcategorías)
        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            if ($category) {
                if ($category->parent_id) {
                    // Es subcategoría - buscar solo en esta subcategoría
                    $query->where('category_id', $category->id);
                } else {
                    // Es categoría padre - buscar en esta categoría Y en todas sus subcategorías
                    $childIds = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                    $allCategoryIds = array_merge([$category->id], $childIds);
                    $query->whereIn('category_id', $allCategoryIds);
                }
            }
        }

        // Ordenamiento
        if ($this->sortBy === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($this->sortBy === 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(9);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('livewire.catalog-search', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function filterByCategory($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryId = null;
        $this->sortBy = 'default';
    }
}
