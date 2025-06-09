<div>
    <flux:modal name="customer-updated" class="md:w-500" wire:ignore.self>
        <form wire:submit.prevent="update">
            <div class="space-y-6">
                <!-- Header -->
                <div>
                    <flux:heading size="lg">Form Update Customer</flux:heading>
                    <flux:text class="mt-2">Update your Customer details below</flux:text>
                </div>
    
                <!-- Menu Name Field -->
                <flux:field>
                    <flux:label>Customer Name</flux:label>
                    <flux:input wire:model="name" placeholder="e.g. Spaghetti Carbonara" />
                    <flux:error for="name" />
                </flux:field>

                <!-- Menu Name Field -->
                <flux:field>
                    <flux:label>Contact</flux:label>
                    <flux:input wire:model="contact" placeholder="08381111222" />
                    <flux:error for="contact" />
                </flux:field>
                
                <!-- Description Field -->
                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="desc" placeholder="Describe the customer item (ingredients, special notes)..." rows="3" />
                    <flux:error for="desc" />
                </flux:field>

                <!-- Image Field (diperbaiki) -->
                <flux:field>
                    <flux:label>customer Image</flux:label>
                    <div class="mt-1">
                        <label class="cursor-pointer">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary-500 transition-colors">
                                @if($image)
                                    <div class="relative">
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="mx-auto h-32 object-cover rounded-lg">
                                        <button type="button" wire:click="$set('image', null)" class="absolute top-0 right-0 -m-2 p-1 bg-red-500 rounded-full text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                @elseif($oldImage)
                                    <div class="relative">
                                        <img src="{{ asset('storage/'.$oldImage) }}" alt="Current Image" class="mx-auto h-32 object-cover rounded-lg">
                                        <button type="button" wire:click="$set('oldImage', null)" class="absolute top-0 right-0 -m-2 p-1 bg-red-500 rounded-full text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <flux:text class="text-sm">Click to upload an image</flux:text>
                                        <flux:text class="text-xs">PNG, JPG, GIF up to 5MB</flux:text>
                                    </div>
                                @endif
                                <input type="file" wire:model="image" class="hidden" accept="image/*">
                            </div>
                        </label>
                    </div>
                    <flux:error for="image" />
                </flux:field>

                <!-- Actions -->
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>                
                        <flux:button variant="ghost">Cancel</flux:button>            
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Update customer</span>
                        <span wire:loading>Updating...</span>
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
    
</div>