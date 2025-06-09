
<div>

    <flux:modal name="create" class="md:w-500">
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Form Input Product</flux:heading>
                    <flux:text class="mt-2">Input your product details below</flux:text>
                </div>
    
                <!-- Menu Name Field -->
                <flux:field>
                    <flux:label>Product Name</flux:label>
                    <flux:input wire:model="name" placeholder="e.g. Spaghetti Carbonara" />
                    <flux:error for="name" />
                </flux:field>
                
                <!-- Price Field -->
                <flux:field>
                    <flux:label>Price</flux:label>
                    <flux:input.group>
                        <flux:select wire:model="price" class="max-w-fit">
                            <flux:select.option value="Rp">Rp</flux:select.option>
                            <flux:select.option value="$">$</flux:select.option>
                        </flux:select>   
                        <flux:input type="number" step="0.01" wire:model="price" placeholder="e.g. 99.99" />
                    </flux:input.group>
                    <flux:error for="price" />
                </flux:field>
                
                <!-- Type Field -->
                <flux:field>
                    <flux:label>Category</flux:label>
                    <flux:select wire:model="category">
                        <flux:select.option value="" disabled selected>Select Product category</flux:select.option>
                        @foreach ($categories as $category)
                            <flux:select.option value="{{ $category }}">{{ $category }}</flux:select.option>  
                        @endforeach
                    </flux:select>
                    <flux:error for="category" />
                </flux:field>
                
                <!-- Description Field -->
                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="desc" placeholder="Describe the Product item (ingredients, special notes)..." rows="3" />
                    <flux:error for="desc" />
                </flux:field>

                <!-- Switch Status/in_stok -->
                <flux:field varian="inline">
                    <flux:label>Status</flux:label>
                    <flux:switch wire:model.live="in_stock" />
                </flux:field>
                
                <!-- Image Upload Field -->
                <flux:field>
                    <flux:label>Product Image</flux:label>
                    <div class="mt-1">
                        <label class="cursor-pointer">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary-500 transition-colors">
                                @if(!$image)
                                    <div class="space-y-2">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <flux:text class="text-sm">Click to upload an image</flux:text>
                                        <flux:text class="text-xs">PNG, JPG, GIF up to 5MB</flux:text>
                                    </div>
                                @else
                                    <div class="relative">
                                        @if(is_string($image))
                                            <img src="{{ asset('storage/'.$image) }}" alt="Product preview" class="mx-auto h-32 object-cover rounded-lg">
                                        @else
                                            <img src="{{ $image->temporaryUrl() }}" alt="Product preview" class="mx-auto h-32 object-cover rounded-lg">
                                        @endif
                                        <flux:button type="button" wire:click="$set('image', null)" variant="ghost" color="red" size="xs" class="absolute top-0 right-0 -m-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </flux:button>
                                    </div>
                                @endif
                                <input type="file" wire:model="image" class="hidden" accept="image/*">
                            </div>
                        </label>
                    </div>
                    <flux:error for="image" />
                    <flux:text class="mt-1 text-sm">Recommended size: 800x600px</flux:text>
                </flux:field>
    
                <!-- Form Actions -->
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="button">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Save Product</span>
                        <span wire:loading>Saving...</span>
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>