<?php

namespace App\Livewire;

use App\Models\Shop\Product;
use Livewire\Component;

class ProductDetailPage extends Component
{
    public $slug;
    public function mount($slug){
        $this->slug = $slug;
    }
    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail(),
        ])->layout('layouts.app');
    }
}
