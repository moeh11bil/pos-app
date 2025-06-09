<div>


<style>
    @media print {
        body * {
            visibility: hidden;  <!-- Sembunyikan semua elemen -->
        }
        #receipt-{{ $transaction->id }}, 
        #receipt-{{ $transaction->id }} * {
            visibility: visible; <!-- Tampilkan hanya struk -->
        }
        #receipt-{{ $transaction->id }} {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

    <!-- Struk yang akan dicetak -->
    <div id="receipt-{{ $transaction->id }}" class="receipt-container bg-white p-4 font-mono" style="width: 80mm;">
        <!-- Header Toko -->
        <div class="text-center mb-2">
            <h1 class="font-bold text-lg">{{ config('app.name') }}</h1>
            <p class="text-xs">{{ config('app.address') }}</p>
            <p class="text-xs">Telp: {{ config('app.phone') }}</p>
        </div>
        
        <!-- Info Transaksi -->
        <div class="border-t border-b border-black py-2 my-2 text-xs">
            <div class="flex justify-between">
                <span>No. Transaksi:</span>
                <span>{{ $transaction->id }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span>{{ auth()->user()->name }}</span>
            </div>
            @if($transaction->customer)
            <div class="flex justify-between">
                <span>Pelanggan:</span>
                <span>{{ $transaction->customer->name }}</span>
            </div>
            @endif
        </div>
        
        <!-- Daftar Item -->
        <div class="border-b border-black py-2 text-xs">
            <div class="grid grid-cols-12 mb-1 font-semibold">
                <div class="col-span-6">ITEM</div>
                <div class="col-span-2 text-right">QTY</div>
                <div class="col-span-4 text-right">HARGA</div>
            </div>
            
            @foreach($transaction->items as $item)
            <div class="grid grid-cols-12">
                <div class="col-span-6">{{ $item['name'] }}</div>
                <div class="col-span-2 text-right">{{ $item['qty'] }}</div>
                <div class="col-span-4 text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        
        <!-- Total Pembayaran -->
        <div class="border-b border-black py-2 text-xs">
            <div class="flex justify-between font-semibold">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Pembayaran:</span>
                <span>{{ $transaction->payment_method ?? 'Tunai' }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-4 text-xs">
            <p>Terima kasih telah berbelanja</p>
            <p class="font-semibold">BARANG YANG SUDAH DIBELI</p>
            <p class="font-semibold">TIDAK DAPAT DIKEMBALIKAN</p>
            <p class="mt-2">=== LAYANAN KONSUMEN ===</p>
            <p>Telp: {{ config('app.phone') }}</p>
        </div>
    </div>

    <!-- Tombol Print -->
    <div class="mt-4 flex justify-center">
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            <span>Cetak Struk</span>
        </button>
    </div>

</div>