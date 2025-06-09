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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Product List Card (Scroll Normal) -->
                <div class="lg:w-1/2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800 pr-2">Products</h2>
                            
                            <div class="flex space-x-2">
                                <!-- Search -->
                                <flux:input 
                                    icon="magnifying-glass" 
                                    placeholder="Search product..." 
                                    wire:model.live.debounce.300ms="search"
                                    class="w-48"
                                />
                                
                                <!-- Category Filter -->
                                <flux:select 
                                    wire:model.live="selectedCategory"
                                    placeholder="All Categories"
                                    class="w-48"
                                >
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>

                        <!-- Product List Grouped by Category -->
                        <div class="space-y-6">
                            @forelse($groupedProducts as $category => $products)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $category }}</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($products as $product)
                                            <div 
                                                class="border rounded-lg p-3 hover:shadow-md transition cursor-pointer"
                                                wire:click="$dispatch('add-to-cart', { productId: {{ $product->id }} })"
                                            >
                                                <div class="h-30 bg-gray-100 rounded-md overflow-hidden">
                                                    @if($product->image)
                                                        <img 
                                                            src="{{ asset('storage/'.$product->image) }}" 
                                                            alt="{{ $product->name }}"
                                                            class="w-full h-full object-cover"
                                                        >
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h4 class="font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                                                <p class="text-sm text-green-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    No products found
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Transaction Details Card (Sticky) -->
                <div class="lg:w-1/2 sticky top-4 h-fit">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">
                            @if($editingTransaction)
                                Edit Transaction #{{ $editingTransaction }}
                            @else
                                Transaction Details
                            @endif
                        </h2>
                        
                        <!-- Items Table -->
                        <div class="overflow-x-auto mb-6" style="max-height: calc(50vh - 100px);">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($editingTransaction ? $editCart : $cart as $id => $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $item['name'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <flux:button 
                                                        variant="outline" 
                                                        size="xs" 
                                                        icon="minus"
                                                        wire:click="{{ $editingTransaction ? 'decrementEditQty('.$id.')' : 'decrementQty('.$id.')' }}"
                                                        :disabled="$item['qty'] <= 1"
                                                    />
                                                    
                                                    <span class="mx-2 w-8 text-center">{{ $item['qty'] }}</span>
                                                    
                                                    <flux:button 
                                                        variant="outline" 
                                                        size="xs" 
                                                        icon="plus"
                                                        wire:click="{{ $editingTransaction ? 'incrementEditQty('.$id.')' : 'incrementQty('.$id.')' }}"
                                                    />
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <flux:button 
                                                    variant="danger" 
                                                    size="sm" 
                                                    icon="trash"
                                                    wire:click="{{ $editingTransaction ? 'removeFromEditCart('.$id.')' : 'removeFromCart('.$id.')' }}"
                                                />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No items in cart
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Customer Selection -->
                        <div class="mb-4">
                            <flux:select 
                                label="Customer"
                                wire:model="{{ $editingTransaction ? 'editCustomer' : 'selectedCustomer' }}"
                                placeholder="Select Customer"
                            >
                                <option value="">No Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <flux:textarea 
                                label="Description"
                                wire:model="{{ $editingTransaction ? 'editDescription' : 'description' }}"
                                placeholder="Transaction notes..."
                                rows="2"
                            />
                        </div>

                        <!-- Total and Submit -->
                        <div class="flex justify-between items-center border-t pt-4">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    Total: Rp {{ number_format($editingTransaction ? collect($editCart)->sum(function($item) { return $item['price'] * $item['qty']; }) : $this->total, 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="flex gap-2">
                                @if($editingTransaction)
                                    <flux:button 
                                        variant="outline" 
                                        wire:click="cancelEdit"
                                    >
                                        Cancel
                                    </flux:button>
                                    <flux:button 
                                        variant="primary" 
                                        icon="check"
                                        wire:click="updateTransaction"
                                        :disabled="empty($editCart)"
                                    >
                                        Update Transaction
                                    </flux:button>
                                @else
                                    <flux:button 
                                        variant="primary" 
                                        icon="check"
                                        wire:click="saveTransaction"
                                        :disabled="empty($cart)"
                                    >
                                        Save Transaction
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('cart-updated', () => {
                // Efek visual ketika cart diupdate
                const cartItems = document.querySelectorAll('#cart-items tr');
                cartItems.forEach(item => {
                    item.classList.add('bg-yellow-50');
                    setTimeout(() => {
                        item.classList.remove('bg-yellow-50');
                    }, 500);
                });
            });

            // Update URL saat edit transaction
            Livewire.on('update-browser-url', (event) => {
                history.pushState(null, null, event.url);
            });
            
            // Scroll ke form saat ada parameter edit
            Livewire.on('scroll-to-form', () => {
                setTimeout(() => {
                    const formSection = document.getElementById('transaction-form');
                    if(formSection) {
                        formSection.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 300);
            });

        });

    </script>
    @endpush
</div>