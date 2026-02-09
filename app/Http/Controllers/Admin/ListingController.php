<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display all listings (admin can see ALL statuses)
     */
    public function index(Request $request)
    {
        $query = Listing::with(['category', 'user']);

        // ✅ Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // ✅ Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $listings = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.listings.index', compact('listings', 'categories'));
    }

    /**
     * Show listing detail for admin
     */
    public function show(Listing $listing)
    {
        $listing->load(['category', 'user']);
        return view('admin.listings.show', compact('listing'));
    }

    /**
     * Approve a pending listing
     */
    public function approve(Listing $listing)
    {
        if ($listing->status !== 'pending') {
            return back()->with('error', 'Only pending listings can be approved.');
        }

        $listing->update(['status' => 'approved']);

        return back()->with('success', 'Listing approved successfully!');
    }

    /**
     * Suspend an approved listing
     */
    public function suspend(Listing $listing)
    {
        if (!in_array($listing->status, ['approved', 'pending'])) {
            return back()->with('error', 'Cannot suspend this listing.');
        }

        $listing->update(['status' => 'suspended']);

        return back()->with('success', 'Listing suspended!');
    }

    /**
     * Restore a suspended listing to pending
     */
    public function restore(Listing $listing)
    {
        if ($listing->status !== 'suspended') {
            return back()->with('error', 'Only suspended listings can be restored.');
        }

        $listing->update(['status' => 'pending']);

        return back()->with('success', 'Listing restored to pending!');
    }

    /**
     * Delete a listing (admin can delete any)
     */
    public function destroy(Listing $listing)
    {
        $listing->delete();

        return redirect()
            ->route('admin.listings.index')
            ->with('success', 'Listing deleted successfully!');
    }
}