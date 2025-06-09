<div>
    <flux:modal name="confirm-delete" maxWidth="md">
        <div class="flex">        
            <div class="flex-1">           
                <flux:heading size="lg">Are you sure?</flux:heading>           
                <flux:text class="mt-2">     
                    <p>Delete product: <strong>{{ $productName }}</strong></p>
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
        </div>
    </flux:modal>
</div>