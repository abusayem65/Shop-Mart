<?php

namespace App\Livewire;

use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home')]
class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::where('is_visible', true)->get();
        $categories = Category::where('is_visible', true)->get();
        return view('livewire.home-page', compact('brands', 'categories'))->layout('layouts.app');
    }
}
