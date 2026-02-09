<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with approved listings
     */
    public function index()
    {
        // Get featured/latest approved listings for home page
        $listings = Listing::with(['category', 'user'])
            ->where('status', 'approved')
            ->latest()
            ->limit(12)
            ->get();
        
        $categories = Category::active()
            ->withCount(['listings' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        return view('home', compact('listings', 'categories'));
    }

    /**
     * Display all listings (browse/search page)
     */
    public function listings()
    {
        return view('listings.index');
    }

    /**
     * Display single listing detail
     */
    public function show(Listing $listing)
    {
        // Only show approved listings to guests/customers
        if ($listing->status !== 'approved') {
            // Check if current user owns this listing
            if (!auth()->check() || auth()->id() !== $listing->user_id) {
                abort(404);
            }
        }

        $listing->load(['category', 'user']);
        
        return view('listings.show', compact('listing'));
    }
}