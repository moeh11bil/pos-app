<x-layouts.app :title="__('Dashboard')">
      @push('scripts') <!-- Pastikan Anda memiliki stack scripts di layouts.app -->
        <script src="{{ mix('js/app.js') }}"></script> <!-- atau asset() jika tidak menggunakan mix -->
    @endpush

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- 3 Card Atas -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Card Pendapatan Bulan Ini -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Pendapatan Bulan Ini</h3>
                    <div class="h-6 w-6 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">
                        Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 flex items-center text-xs text-neutral-500 dark:text-neutral-400">
                        @if($monthlyGrowth >= 0)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <span class="text-green-500">{{ round($monthlyGrowth, 2) }}% dari bulan lalu</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                            <span class="text-red-500">{{ round(abs($monthlyGrowth), 2) }}% dari bulan lalu</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Card Pendapatan Hari Ini -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Pendapatan Hari Ini</h3>
                    <div class="h-6 w-6 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">
                        Rp {{ number_format($dailyRevenue, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                        {{ round($dailyPercentage, 2) }}% dari target harian
                    </p>
                    <div class="mt-2 w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($dailyPercentage, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Card Pesanan -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Pemesan Hari Ini</h3>
                    <div class="h-6 w-6 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-2xl font-semibold text-neutral-900 dark:text-white">
                        {{ $customerCount }} Pelanggan
                    </p>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        Produk terpopuler: {{ $popularProduct }}
                    </p>
                    <div class="mt-3 inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/50 px-3 py-1 text-xs font-medium text-purple-800 dark:text-purple-200">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Besar Bawah -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-neutral-900 dark:text-white">Grafik Pendapatan 30 Hari Terakhir</h2>
                
            </div>
            
            <div class="bg-white rounded-lg shadow px-4 py-12 h-100">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pendapatan 30 Hari Terakhir</h3>
                <div class="w-full h-full">
                    <x-chart.line 
                        :labels="$revenueChart['labels']" 
                        :datasets="$revenueChart['datasets']"
                        :aspectRatio="3"
                    />
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-between border-t border-neutral-200 dark:border-neutral-700 pt-4">
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    <span class="font-medium text-neutral-900 dark:text-white">Total 30 hari:</span>
                    Rp {{ number_format(array_sum($revenueChart['datasets'][0]['data']->toArray()), 0, ',', '.') }}

                </p>
                <div class="flex space-x-4">
                    <button class="inline-flex items-center text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>