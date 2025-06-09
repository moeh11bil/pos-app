<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;

class Delete extends Component
{
    public $productId;
    public $productName;

    protected $listeners = ['deletet' => 'confirmDelete'];

    public function confirmDelete($id)
    {
        $product = Product::find($id);
        $this->productId = $id;
        $this->productName = $product->name;
        Flux::modal('confirm-delete')->show();
    }

    public function delete()
    {
        $product = Product::find($this->productId);
        
        // Delete the associated image file if it exists
        if ($product->image && Storage::exists($product->image)) {
            Storage::delete($product->image);
        }
        
        // Delete the product record
        $product->delete();
        
        Flux::modal('confirm-delete')->close();
        session()->flash('success', 'Product deleted successfully');
        $this->redirectRoute('product', navigate: true);
    }

    public function render()
    {
        return view('livewire.product.delete');
    }
}