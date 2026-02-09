<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Provider\ListingController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboardController;
use App\Http\Controllers\Provider\EnquiryController as ProviderEnquiryController;
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
    Route::get('/my-enquiries', [EnquiryController::class, 'index'])->name('customer.enquiries.index');
    Route::get('/my-enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('customer.enquiries.show');
    Route::post('/my-enquiries/{enquiry}/reply', [EnquiryController::class, 'reply'])->name('customer.enquiries.reply');
});

//  Admin routes (ONLY admins can access)

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Listing Management (Livewire handles all actions)
    Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/{listing}', [AdminListingController::class, 'show'])->name('listings.show');
});


// Provider routes (ONLY providers can access)
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
    
    // Listing CRUD (Resource routes)
    Route::resource('listings', ListingController::class);
    // Enquiry Management
    Route::get('/enquiries', [ProviderEnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/{enquiry}', [ProviderEnquiryController::class, 'show'])->name('enquiries.show');
    Route::post('/enquiries/{enquiry}/reply', [ProviderEnquiryController::class, 'reply'])->name('enquiries.reply');
    Route::patch('/enquiries/{enquiry}/close', [ProviderEnquiryController::class, 'close'])->name('enquiries.close');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
