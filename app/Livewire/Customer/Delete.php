<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;

class Delete extends Component
{
    public $customerId;
    public $customerName;

    protected $listeners = ['deletet' => 'confirmDelete'];

    public function confirmDelete($id)
    {
        $customer = Customer::find($id);
        $this->customerId = $id;
        $this->customerName = $customer->name;
        Flux::modal('confirm-delete')->show();
    }

    public function delete()
    {
        $customer = Customer::find($this->customerId);
        
        // Delete the associated image file if it exists
        if ($customer->image && Storage::exists($customer->image)) {
            Storage::delete($customer->image);
        }
        
        // Delete the customer record
        $customer->delete();
        
        Flux::modal('confirm-delete')->close();
        session()->flash('success', 'Customer deleted successfully');
        $this->redirectRoute('customer', navigate: true);
    }

    public function render()
    {
        return view('livewire.customer.delete');
    }
}