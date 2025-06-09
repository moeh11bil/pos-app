<?php

use Livewire\Volt\Volt;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Product Route
    Route::get('product', \App\Livewire\Product\Index::class)->name('product');
    Route::get('/customer', \App\Livewire\Customer\Index::class)->name('customer');
    Route::get('/transaction', \App\Livewire\Transaction\Index::class)->name('transaction');
    Route::get('/transaction/shipped', \App\Livewire\Transaction\Shipped::class)->name('transaction.shipped');

    Route::get('/transaction/{transaction}/print', function (Transaction $transaction) {
    return app()->call(\App\Livewire\Transaction\Printed::class, ['transactionId' => $transaction->id]);
})->name('transaction.print');
    
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
