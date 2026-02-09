<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
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
    public function store(Request $request)
    {
        $this->authorize('create', Listing::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'city' => 'required|string|max:100',
            'suburb' => 'required|string|max:100',
            'pricing_type' => 'required|in:hourly,fixed',
            'price_amount' => 'required|numeric|min:0|max:999999.99',
        ]);

        // ✅ Determine status based on button clicked
        $status = $request->input('action') === 'draft' ? 'draft' : 'pending';

        $listing = Listing::create([
            'user_id' => auth()->id(),
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'status' => $status,
            ...$validated,
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
    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'city' => 'required|string|max:100',
            'suburb' => 'required|string|max:100',
            'pricing_type' => 'required|in:hourly,fixed',
            'price_amount' => 'required|numeric|min:0|max:999999.99',
        ]);

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