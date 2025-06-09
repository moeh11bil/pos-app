<?php

namespace App\Livewire\Transaction;

use Flux\Flux;
use App\Models\Product;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

class Index extends Component
{
    public $search = '';
    public $selectedCustomer = null;
    public $description = '';
    public Collection $products;
    public array $cart = [];
    public array $categories = [];
    public $selectedCategory = '';

    // edited
    public $editingTransaction = null;
    public $editCart = [];
    public $editDescription = '';
    public $editCustomer = null;
    

    public function mount()
    {
        $this->products = Product::all();
        $this->categories = Product::distinct('category')->pluck('category')->toArray();
        
        // Cek jika ada parameter edit
        if(request()->has('edit')) {
            $this->editTransaction(request()->query('edit'));
        }
    }

    #[On('add-to-cart')]
    public function addToCart($productId, $qty = 1)
    {
        $product = Product::find($productId);
        
        // Cek mode edit atau create
        $targetCart = $this->editingTransaction ? 'editCart' : 'cart';
        
        if (isset($this->{$targetCart}[$productId])) {
            $this->{$targetCart}[$productId]['qty'] += $qty;
        } else {
            $this->{$targetCart}[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $qty
            ];
        }

         // Jika mode edit, update total
        if ($this->editingTransaction) {
            $this->updateTransactionTotal();
        }
    }

    protected function updateTransactionTotal()
    {
        if ($this->editingTransaction) {
            $transaction = Transaction::find($this->editingTransaction);
            if ($transaction) {
                $transaction->update([
                    'price' => collect($this->editCart)->sum(function($item) {
                        return $item['price'] * $item['qty'];
                    })
                ]);
            }
        }
    }

    public function incrementQty($productId)
    {
        $this->addToCart($productId, 1);
    }

    public function decrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] > 1) {
                $this->cart[$productId]['qty']--;
            } else {
                $this->removeFromCart($productId);
            }
        }
    }

    // increment decrement edit qty

    public function incrementEditQty($productId)
    {
        if (isset($this->editCart[$productId])) {
            $this->editCart[$productId]['qty']++;
        }
    }

    public function decrementEditQty($productId)
    {
        if (isset($this->editCart[$productId])) {
            if ($this->editCart[$productId]['qty'] > 1) {
                $this->editCart[$productId]['qty']--;
            } else {
                $this->removeFromEditCart($productId);
            }
        }
    }

    public function removeFromEditCart($productId)
    {
        unset($this->editCart[$productId]);
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function($item) {
            return $item['price'] * $item['qty'];
        });
    }

   public function saveTransaction()
    {
        $this->validate([
            'cart' => 'required|array|min:1', // Hanya validasi cart wajib
            'selectedCustomer' => 'nullable|exists:customers,id' // Ubah menjadi nullable
        ]);

        $transaction = Transaction::create([
            'customer_id' => $this->selectedCustomer, // Bisa null
            'items' => $this->cart,
            'desc' => $this->description,
            'price' => $this->total,
            'done' => false
        ]);

        $this->reset(['cart', 'description', 'selectedCustomer']);
        session()->flash('success', 'Transaction saved successfully!');
    }

    public function editTransaction($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        $this->editingTransaction = $transactionId;
        $this->editCart = $transaction->items;
        $this->editDescription = $transaction->desc;
        $this->editCustomer = $transaction->customer_id;
        
        // Scroll ke form setelah render
        $this->dispatch('scroll-to-form');
    }
    public function updateTransaction()
    {
        $this->validate([
            'editCustomer' => 'nullable|exists:customers,id',
            'editCart' => 'required|array|min:1'
        ]);

        $transaction = Transaction::findOrFail($this->editingTransaction);
        
        $transaction->update([
            'customer_id' => $this->editCustomer,
            'items' => $this->editCart,
            'desc' => $this->editDescription,
            'price' => collect($this->editCart)->sum(function($item) {
                return $item['price'] * $item['qty'];
            })
        ]);

        $this->cancelEdit();
        session()->flash('success', 'Transaction updated successfully!');
        $this->redirectRoute('transaction.shipped', navigate: true);
    }

    public function cancelEdit()
    {
        $this->editingTransaction = null;
        $this->editCart = [];
        $this->editDescription = '';
        $this->editCustomer = null;
        
        // Reset URL tanpa parameter edit
        $this->dispatch('update-browser-url', url: route('transaction.shipped'));
    }


    public function render()
    {
        $filteredProducts = $this->products
            ->when($this->search, function($collection) {
                return $collection->filter(function($item) {
                    return str_contains(strtolower($item->name), strtolower($this->search));
                });
            })
            ->when($this->selectedCategory, function($collection) {
                return $collection->where('category', $this->selectedCategory);
            })
            ->groupBy('category');

        $customers = Customer::all();

        return view('livewire.transaction.index', [
            'groupedProducts' => $filteredProducts,
            'customers' => $customers
        ]);
    }
}
