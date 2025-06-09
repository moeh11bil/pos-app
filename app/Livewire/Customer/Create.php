<?php

namespace App\Livewire\Customer;

use Flux\Flux;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    public $onSave;

    public $name;
    public $desc;
    public $contact;
    public $image;


    protected $rules = [
        'name' => 'required|string|max:255',
        'desc' => 'nullable|string',
        'contact' => 'required|numeric|min:0',
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
            'contact' => $this->contact
        ];

        if ($this->image) {
            // Simpan dengan path lengkap
            $path = $this->image->store('customer-images', 'public');
            $data['image'] = $path; // Format: 'customer-images/nama-file.jpg'
            
            logger()->info('Image stored:', [
                'db_path' => $path,
                'full_path' => storage_path('app/public/'.$path)
            ]);
        }

        $customer = Customer::create($data);
        
        session()->flash('success', 'Customer Created!');
        return redirect()->route('customer');

    } catch (\Exception $e) {
        logger()->error('Create error:', ['error' => $e->getMessage()]);
        session()->flash('error', 'Failed: '.$e->getMessage());
    }
}
    public function render()
    {
        return view('livewire.customer.create');
    }
}