<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;

class ListingController extends Controller
{
    /**
     * Display all listings (Livewire handles search/filter/actions)
     */
    public function index()
    {
        return view('admin.listings.index');
    }

    /**
     * Show listing detail for admin
     */
    public function show(Listing $listing)
    {
        $listing->load(['category', 'user']);
        return view('admin.listings.show', compact('listing'));
    }
}