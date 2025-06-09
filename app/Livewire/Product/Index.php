<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 5;
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 5],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = [
        'product-deleted' => '$refresh',
        'product-updated' => '$refresh',
    ];

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function edit($id)
    {
        $this->dispatch('edit-product', id: $id)->to(Edit::class);
        Flux::modal('product-updated')->show();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('deletet', id: $id)->to(Delete::class);
    }

    public function render()
    {
        if (session()->has('success')) {
            $this->dispatch('show-toast', message: session('success'), type: 'success');
        }

        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('category', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.product.index', compact('products'));
    }
}