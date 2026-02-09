<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Provider\ListingController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\EnquiryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/listings', [HomeController::class, 'listings'])->name('listings.index');
Route::get('/listings/{listing:slug}', [HomeController::class, 'show'])->name('listings.show');

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/enquiries', [EnquiryController::class, 'store'])->name('enquiries.store');
    Route::get('/enquiries/captcha', [EnquiryController::class, 'generateCaptcha'])->name('enquiries.captcha');
});

//  Admin routes (ONLY admins can access)

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $stats = [
            'total_listings' => \App\Models\Listing::count(),
            'pending_listings' => \App\Models\Listing::where('status', 'pending')->count(),
            'approved_listings' => \App\Models\Listing::where('status', 'approved')->count(),
            'suspended_listings' => \App\Models\Listing::where('status', 'suspended')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');
    
    // Listing Management (Livewire handles all actions)
    Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/{listing}', [AdminListingController::class, 'show'])->name('listings.show');
});


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
