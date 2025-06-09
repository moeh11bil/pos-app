<div>
    
    <livewire:customer.create />
    <livewire:customer.delete />
    <livewire:customer.edit />

     @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
@endif
     

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-4">

                        <!-- Search dan Pagination -->
                        <flux:input 
                            icon="magnifying-glass" 
                            placeholder="Search customer..." 
                            wire:model.live.debounce.300ms="search"
                            class="w-64"
                        />
            
                        <div class="flex items-center space-x-2">
                            <flux:select 
                                wire:model.live="perPage" 
                                class="w-20"
                            >
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </flux:select>
                            <span class="text-sm text-gray-500">per page</span>
                        </div>
                    </div>
                  
                    
                    <flux:modal.trigger name="create">
                        <flux:button variant="primary" icon="plus" wire:click="customer.create" class="cursor-pointer">Add Customer</flux:button>
                    </flux:modal.trigger>
                </div>

               
                <!-- Tabel customer -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <!-- Header Table -->
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div wire:click="sortBy('name')" class="flex items-center cursor-pointer">
                                        Customer Name
                                        @if($sortField === 'name')
                                            @if($sortDirection === 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </th>
                                <th scope="col" wire:click="sortBy('contact')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th scope="col" wire:click="sortBy('desc')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        
                        <!-- Body Table -->
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($customers as $customer)
                                <tr wire:key="customer-{{ $customer->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($customer->image)
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ asset('storage/'.$customer->image) }}" 
                                                    alt="{{ $customer->name }}">
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    No Image
                                                </div>                   
                                            @endif
    
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $customer->contact }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="text-sm text-gray-500">{{ Str::limit($customer->desc, 30) }}</span>
                                    </td>
                                  
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                       
                                        <flux:button 
                                            variant="outline" 
                                            size="sm" 
                                            icon="pencil"
                                            wire:click.prevent="edit({{ $customer->id }})"
                                            title="Edit"
                                        />

                                         <flux:button 
                                            variant="danger" 
                                            size="sm" 
                                            icon="trash"
                                            wire:click.prevent="confirmDelete({{ $customer->id }})"
                                            title="Delete"
                                            class="cursor-pointer"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data customer ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $customers->links('livewire::tailwind') }}
                </div>

            </div>

        </div>
    </div>
 
</div>


