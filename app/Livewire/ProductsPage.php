<?php

namespace App\Livewire;

use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPage extends Component
{
    use WithPagination;
    #[Url]
    public $selected_categories = [];
    public $selected_brands = [];

    public function render()
    {
        $productsQuery = Product::where('is_visible', true);

        if (!empty($this->selected_categories)) {
            $productsQuery->whereHas('categories', function ($query) {
                $query->whereIn('shop_category_id', $this->selected_categories);
            });
        }
        if (!empty($this->selected_brands)) {
            $productsQuery->whereIn('shop_brand_id', $this->selected_brands);
        }
        return view('livewire.products-page', [
            'products' => $productsQuery->paginate(10),
            'brands' => Brand::where('is_visible', true)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_visible', true)->get(['id', 'name', 'slug']),
        ])->layout('layouts.app');
    }
}
