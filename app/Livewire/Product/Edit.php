<?php

namespace App\Livewire\Product;

use Flux\Flux;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $productId;
    public $name;
    public $desc;
    public $price;
    public $currency = 'Rp';
    public $category;
    public $in_stock = true;
    public $image;
    public $oldImage;

    public $categories = [
        'coffee',
        'tea',
        'drink',
        'food',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'desc' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'category' => 'required|string|max:255',
        'in_stock' => 'boolean',
        'image' => 'nullable|image|max:2048',
    ];

    #[On('edit-product')]
    public function loadProduct($id)
    {
        $this->productId = $id;
        $this->initializeData();
    }

    public function initializeData()
    {
        if ($this->productId) {
            $product = Product::find($this->productId);
            
            if ($product) {
                $this->name = $product->name;
                $this->desc = $product->desc;
                $this->price = $product->price;
                $this->category = $product->category;
                $this->in_stock = (bool)$product->in_stock;
                $this->oldImage = $product->image;
            }
        }
    }

   public function update()
    {
        $this->validate();

        try {
            $product = Product::findOrFail($this->productId);
            
            $data = [
                'name' => $this->name,
                'desc' => $this->desc,
                'price' => $this->price,
                'category' => $this->category,
                'in_stock' => $this->in_stock,
            ];

            if ($this->image) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $this->image->store('product-images', 'public');
            }

            $product->update($data);
            Flux::modal('product-updated')->close();

            // Simpan flash message sebelum menutup modal
            session()->flash('success', 'Product updated successfully!');
            
            $this->redirectRoute('product', navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update product: '.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.product.edit');
    }
}