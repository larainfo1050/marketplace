<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Provider\ListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Provider routes (ONLY providers can access)
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $stats = [
            'total_listings' => auth()->user()->listings()->count(),
            'pending_listings' => auth()->user()->listings()->where('status', 'pending')->count(),
            'approved_listings' => auth()->user()->listings()->where('status', 'approved')->count(),
            'draft_listings' => auth()->user()->listings()->where('status', 'draft')->count(),
        ];
        return view('provider.dashboard', compact('stats'));
    })->name('dashboard');
    
    // Listing CRUD (Resource routes)
    Route::resource('listings', ListingController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
