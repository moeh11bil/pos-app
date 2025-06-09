<?php

namespace App\Livewire\Product;

use Flux\Flux;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    public $onSave;

    public $name;
    public $desc;
    public $price;
    public $category = 'coffee';
    public $in_stock = true;
    public $image;


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

    protected $listeners = ['close-modal' => 'resetForm'];

    public function resetForm()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'desc' => $this->desc,
                'price' => $this->price,
                'category' => $this->category,
                'in_stock' => $this->in_stock,
            ];
    
            if ($this->image) {
                $data['image'] = $this->image->store('product-images', 'public');
            }
    
            Product::create($data);
    
            $this->resetForm();
            Flux::modal('product-create')->close();

            // Simpan flash message sebelum menutup modal
            session()->flash('success', 'Product created successfully!');
            
            $this->redirectRoute('product', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create product');
        }
    }
    public function render()
    {
        return view('livewire.product.create');
    }
}