<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
       
        // Data bulan ini
        $monthlyRevenue = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('price');

        $monthlyGrowth = $this->calculateGrowth(
            Transaction::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('price'),
            $monthlyRevenue
        );

        // Data hari ini
        $dailyRevenue = Transaction::whereDate('created_at', today())->sum('price');
        $dailyTarget = 5000000; // Target harian
        $dailyPercentage = $dailyTarget > 0 ? ($dailyRevenue / $dailyTarget) * 100 : 0;

        // Data pemesan
        $customerCount = Transaction::whereDate('created_at', today())
            ->select(DB::raw('COUNT(DISTINCT COALESCE(customer_id, id)) as count')) 
            ->value('count');

        $popularProduct = Transaction::whereDate('created_at', today())
            ->get()
            ->flatMap(fn($t) => $t->items)
            ->groupBy('name')
            ->map(fn($items) => count($items))
            ->sortDesc()
            ->keys()
            ->first() ?? '-';

        // Data untuk chart
        $revenueData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(price) as total')
            )
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

         return view('dashboard', [
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyGrowth' => $monthlyGrowth,
            'dailyRevenue' => $dailyRevenue,
            'dailyPercentage' => $dailyPercentage,
            'customerCount' => $customerCount,
            'popularProduct' => $popularProduct,
            'revenueChart' => [
                'labels' => $revenueData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M')),
                'datasets' => [
                    [
                        'label' => 'Pendapatan',
                        'data' => $revenueData->pluck('total'),
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4
                    ]
                ]
            ],
          
        ]);
    }

    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0) {
            return $current == 0 ? 0 : 100;
        }
        return (($current - $previous) / $previous) * 100;
    }
}