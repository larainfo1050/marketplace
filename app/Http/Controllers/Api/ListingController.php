<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of listings (with search/filter)
     */
    public function index(Request $request)
    {
        $query = Listing::with(['category', 'user'])
            ->approved();

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price_amount', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price_amount', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        match($sort) {
            'price_asc' => $query->orderBy('price_amount', 'asc'),
            'price_desc' => $query->orderBy('price_amount', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return response()->json($query->paginate(20));
    }

    /**
     * Store a newly created listing (Provider only)
     */
    public function store(StoreListingRequest $request)
    {
        $this->authorize('create', Listing::class);

        // Determine status based on action parameter
        $status = $request->input('action') === 'draft' ? 'draft' : 'pending';

        $listing = Listing::create([
            'user_id' => auth()->id(),
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'status' => $status,
            ...$request->validated(),
        ]);

        $listing->load(['category', 'user']);

        return response()->json([
            'message' => $status === 'draft' 
                ? 'Listing saved as draft.' 
                : 'Listing submitted for approval.',
            'listing' => $listing,
        ], 201);
    }

    /**
     * Display the specified listing
     */
    public function show(Listing $listing)
    {
        $this->authorize('view', $listing);
        
        $listing->load(['category', 'user']);
        
        return response()->json($listing);
    }

    /**
     * Update the specified listing (Owner only)
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validated();

        // Only allow status change from draft → pending
        if ($listing->status === 'draft') {
            $validated['status'] = $request->input('action') === 'draft' ? 'draft' : 'pending';
        }

        $listing->update($validated);
        $listing->load(['category', 'user']);

        return response()->json([
            'message' => isset($validated['status']) && $validated['status'] === 'pending'
                ? 'Listing submitted for approval!'
                : 'Listing updated successfully!',
            'listing' => $listing,
        ]);
    }

    /**
     * Remove the specified listing (Owner only)
     */
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);
        
        $listing->delete();
        
        return response()->json([
            'message' => 'Listing deleted successfully!',
        ]);
    }

    /**
     * Get authenticated provider's listings (draft, pending, approved, etc.)
     */
    public function myListings(Request $request)
    {
        $query = auth()->user()->listings()->with('category');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(20));
    }
}