<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Flux\Flux;

class Shipped extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $transactionId;
    public $transactionName;

    public $selectedTransaction = null;


    protected $listeners = ['refresh' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

   public function toggleDone(Transaction $transaction)
    {
    
        $transaction->done = !$transaction->done;
        $transaction->save();
        
    
        // Notifikasi session
        session()->flash('success', 'Transaction update');
    }

    public function editTransaction($transactionId)
    {
        // Redirect ke halaman transaction.index dengan parameter edit
        return redirect()->route('transaction', [
            'edit' => $transactionId
        ]);
    }

    public function confirmDelete($id)
    {
        $transaction = Transaction::find($id);
        $this->transactionId = $id;
        $this->transactionName = $transaction->name;
        Flux::modal('shipped-delete')->show();
    }

    public function delete()
    {
        $transaction = Transaction::find($this->transactionId);
    
        // Delete the transaction record
        $transaction->delete();
        
        Flux::modal('shipped-delete')->close();
        session()->flash('success', 'transaction deleted successfully');
        $this->redirectRoute('transaction.shipped', navigate: true);
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('desc', 'like', '%' . $this->search . '%');
            })
            ->when($this->dateFrom && $this->dateTo, function ($query) {
                $query->whereBetween('created_at', [
                    $this->dateFrom . ' 00:00:00',
                    $this->dateTo . ' 23:59:59'
                ]);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.transaction.shipped', [
            'transactions' => $transactions
        ]);
    }



}
