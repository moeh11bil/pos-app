<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use App\Models\Transaction;

class Printed extends Component
{
    public $transactionId;
    public $transaction;

    public function mount($transactionId)
    {
        $this->transactionId = $transactionId;
        $this->transaction = Transaction::with('customer')->findOrFail($transactionId);
    }

    public function printReceipt()
    {
        $this->dispatch('print-receipt', transactionId: $this->transaction->id);
    }

    public function render()
    {
        return view('livewire.transaction.printed');
    }
}