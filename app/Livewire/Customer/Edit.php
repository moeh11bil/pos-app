<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $customer;
    public $name;
    public $contact;
    public $desc;
    public $image;
    public $oldImage;

    

    protected $rules = [
        'name' => 'required|string|max:255',
        'desc' => 'nullable|string',
        'contact' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
    ];

    #[On('edit-customer')]
    public function loadCustomer($id)
    {
        $this->customer = $id;
        $this->initializeData();
    }

    public function initializeData()
    {
        if ($this->customer) {
            $customer = Customer::find($this->customer);
            
            if ($customer) {
                $this->name = $customer->name;
                $this->contact = $customer->contact;
                $this->desc = $customer->desc;
                $this->oldImage = $customer->image;
            }
        }
    }

   public function update()
    {
        $this->validate();

        try {
            $customer = Customer::findOrFail($this->customer);
            
            $data = [
                'name' => $this->name,
                'desc' => $this->desc,
                'contact' => $this->contact,
                
            ];

            if ($this->image) {
                if ($customer->image) {
                    Storage::disk('public')->delete($customer->image);
                }
                $data['image'] = $this->image->store('customer-images', 'public');
            }

            $customer->update($data);
            Flux::modal('customer-updated')->close();

            // Simpan flash message sebelum menutup modal
            session()->flash('success', 'Customer updated successfully!');
            
            $this->redirectRoute('customer', navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update customer: '.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.customer.edit');
    }
}