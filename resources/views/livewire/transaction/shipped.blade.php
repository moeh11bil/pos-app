<div>
    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search -->
            <div class="w-full md:w-64 mt-6">
                <flux:input 
                    icon="magnifying-glass" 
                    placeholder="Search customer or notes..." 
                    wire:model.live.debounce.300ms="search"
                />
            </div>
            
            <!-- Date Range -->
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="w-full sm:w-48">
                    <flux:input 
                        type="date"
                        label="From"
                        wire:model.live="dateFrom"
                    />
                </div>
                <div class="w-full sm:w-48">
                    <flux:input 
                        type="date"
                        label="To"
                        wire:model.live="dateTo"
                    />
                </div>
            </div>
            
            <!-- Per Page -->
            <div class="w-full md:w-32">
                <flux:select 
                    wire:model.live="perPage"
                    label="Per Page"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                            <div class="flex items-center">
                                Date
                                @if($sortField === 'created_at')
                                    <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="ml-1 h-4 w-4" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('customer_id')">
                            <div class="flex items-center">
                                Customer
                                @if($sortField === 'customer_id')
                                    <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="ml-1 h-4 w-4" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Items
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Desc
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('price')">
                            <div class="flex items-center">
                                Total
                                @if($sortField === 'price')
                                    <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="ml-1 h-4 w-4" />
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr wire:key="transaction-{{ $transaction->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('d M Y H:i') }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transaction->customer?->name ?? 'Guest' }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ count($transaction->items) }} items
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction?->desc ?? ''}}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                Rp {{ number_format($transaction->price, 0, ',', '.') }}
                            </td>
                            
                           <!-- Kolom Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                            class="sr-only peer" 
                                            wire:model="transaction.done"
                                            wire:change="toggleDone({{ $transaction->id }})"
                                            @checked($transaction->done)>
                                        <div class="w-7 h-4 bg-gray-300 peer-focus:outline-none rounded-full peer 
                                            peer-checked:bg-slate-900 peer-checked:after:translate-x-full 
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                            after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all">
                                        </div>
                                    </label>
                                    <span class="ml-2 text-sm font-medium {{ $transaction->done ? 'text-slate-900' : 'text-gray-600' }}">
                                        {{ $transaction->done ? 'Completed' : 'Processing' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                <div class="flex justify-end gap-2">
                                    <flux:modal.trigger name="view-transaction-{{ $transaction->id }}">
                                        <flux:button variant="outline" size="sm" icon="eye" title="View" />
                                    </flux:modal.trigger>

                                   <div x-data="{ printModalOpen: false }">
                                        <flux:button 
                                            variant="outline" 
                                            size="sm" 
                                            icon="printer" 
                                            title="Cetak Struk"
                                            @click="printModalOpen = true"
                                        />
                                        
                                        <template x-teleport="body">
                                            <div 
                                                x-show="printModalOpen" 
                                                @keydown.escape.window="printModalOpen = false"
                                                x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 z-50 overflow-y-auto"
                                            >
                                                <!-- Overlay -->
                                                <div class="fixed inset-0 bg-black/50" @click="printModalOpen = false"></div>
                                                
                                                <!-- Modal Container -->
                                                <div class="flex min-h-full items-center justify-center p-4">
                                                    <!-- Modal Content - Sesuaikan lebar dengan kebutuhan struk -->
                                                    <div 
                                                        class="relative bg-white rounded-lg shadow-xl w-full max-w-[85mm] mx-auto p-0"
                                                        @click.outside="printModalOpen = false"
                                                    >
                                                        <!-- Tombol Close -->
                                                        <button 
                                                            @click="printModalOpen = false"
                                                            class="absolute top-2 right-2 p-1 rounded-full hover:bg-gray-100 text-gray-500"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Konten Struk -->
                                                        <div class="p-4">
                                                            <livewire:transaction.printed 
                                                                :transactionId="$transaction->id" 
                                                                wire:key="print-{{ $transaction->id }}"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    
                            @if($transaction->done)
                                        <flux:button 
                                            variant="danger" 
                                            size="sm" 
                                            icon="trash" 
                                            title="Delete"
                                            wire:click="confirmDelete({{ $transaction->id }})"
                                        
                                        />
                                    @else
                                        <flux:button 
                                            variant="outline" 
                                            size="sm" 
                                            icon="pencil"
                                            title="Edit"
                                            wire:click="editTransaction({{ $transaction->id }})"
                                            wire:navigate
                                        />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No shipped transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $transactions->links('livewire::tailwind') }}
    </div>

    <!-- View Modal -->
    @foreach($transactions as $transaction)
        <flux:modal name="view-transaction-{{ $transaction->id }}" class="md:w-100">
            <h3 class="font-bold text-lg">Detail Transaction</h3>
            <div class="py-4 space-y-4">
                <!-- Existing detail view -->
                <div class="flex flex-col">
                    <div class="text-sm opacity-50">Transaction Date</div>
                    <div>{{ $transaction->created_at->format('d F Y H:i')}}</div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm opacity-50">Customer Name</div>
                    <div>{{ $transaction->customer?->name ?? "-"}}</div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm opacity-50">Total Price</div>
                    <div>Rp. {{ number_format($transaction->price, 0, ',', '.') }}</div>
                </div>

                <div class="table-wrapper">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th>Menu Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transaction->items ?? [] as $key => $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['qty'] }}</td>
                                    <td>{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Form Actions -->
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="button">Cancel</flux:button>
                    </flux:modal.close>

                </div>
            </div>
        </flux:modal>
    @endforeach


    <!-- Modal delete -->
    <flux:modal name="shipped-delete" maxWidth="md">
        <div class="flex">        
            <div class="flex-1">           
                <flux:heading size="lg">Are you sure?</flux:heading>           
                <flux:text class="mt-2">     
                    <p>Delete transaction: <strong>{{ $transactionName }}</strong></p>
                    <p>This action cannot be undone.</p>            
                </flux:text>        
            </div>        
        </div>
        <div class="flex gap-4 mt-4">       
            <flux:spacer />  
                <flux:modal.close>
                    <flux:button>Cancel</flux:button>        
                </flux:modal.close>      
                <flux:button type="button" variant="danger" wire:click="delete">Delete</flux:button>   
            </flux:modal>
        </div>

</div>