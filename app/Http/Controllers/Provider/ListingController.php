<?php

namespace App\Http\Controllers\Provider;

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
     * Display a listing of the provider's listings
     */
    public function index()
    {
        return view('provider.listings.index');
    }

    /**
     * Show the form for creating a new listing
     */
    public function create()
    {
        $this->authorize('create', Listing::class);

        $categories = Category::active()->get();
        return view('provider.listings.create', compact('categories'));
    }

    /**
     * Store a newly created listing
     */
    public function store(StoreListingRequest $request)
    {
        $this->authorize('create', Listing::class);

        // ✅ Determine status based on button clicked (UI logic, not validation)
        $status = $request->input('action') === 'draft' ? 'draft' : 'pending';

        $listing = Listing::create([
            'user_id' => auth()->id(),
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'status' => $status,
            ...$request->validated(),
        ]);

        $message = $status === 'draft' 
            ? 'Listing saved as draft. You can submit it for approval later.'
            : 'Listing submitted successfully! Waiting for admin approval.';

        return redirect()
            ->route('provider.listings.index')
            ->with('success', $message);
    }

    /**
     * Display the specified listing
     */
    public function show(Listing $listing)
    {
        $this->authorize('update', $listing);
        $listing->load('category');
        return view('provider.listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the listing
     */
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);
        $categories = Category::active()->get();
        return view('provider.listings.edit', compact('listing', 'categories'));
    }

    /**
     * Update the listing
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validated();

        // ✅ Only allow status change from draft → pending
        if ($listing->status === 'draft') {
            $validated['status'] = $request->input('action') === 'draft' ? 'draft' : 'pending';
        }

        $listing->update($validated);

        $message = isset($validated['status']) && $validated['status'] === 'pending'
            ? 'Listing submitted for approval!'
            : 'Listing updated successfully!';

        return redirect()
            ->route('provider.listings.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified listing
     */
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);
        $listing->delete();
        return redirect()
            ->route('provider.listings.index')
            ->with('success', 'Listing deleted successfully!');
    }
}