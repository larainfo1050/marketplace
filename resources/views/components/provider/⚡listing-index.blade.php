{{-- filepath: resources/views/components/provider/⚡listing-index.blade.php --}}
<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination;

    // ✅ URL query parameters (shareable links)
    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category_id = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $sort = 'newest';

    #[Url]
    public int $min_price = 0;

    #[Url]
    public int $max_price = 10000;

    // ✅ Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCity()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    /**
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->reset([
            'search',
            'category_id',
            'status',
            'city',
            'sort',
            'min_price',
            'max_price',
        ]);
        $this->resetPage();
    }

    /**
     * Delete a listing
     */
    public function deleteListing($listingId)
    {
        $listing = Listing::findOrFail($listingId);
        
        // Authorization check
        if ($listing->user_id !== auth()->id()) {
            abort(403);
        }

        $listing->delete();

        session()->flash('success', 'Listing deleted successfully!');
    }

    public function render()
    {
        // ✅ Build query for provider's own listings
        $query = auth()->user()
            ->listings()
            ->with('category');

        // ✅ Keyword Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // ✅ Filter by Category
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        // ✅ Filter by Status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // ✅ Filter by City
        if ($this->city) {
            $query->where('city', 'like', "%{$this->city}%");
        }

        // ✅ Filter by Price Range
        if ($this->min_price > 0) {
            $query->where('price_amount', '>=', $this->min_price);
        }
        if ($this->max_price < 10000) {
            $query->where('price_amount', '<=', $this->max_price);
        }

        // ✅ Sorting
        match($this->sort) {
            'price_asc' => $query->orderBy('price_amount', 'asc'),
            'price_desc' => $query->orderBy('price_amount', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            'title' => $query->orderBy('title', 'asc'),
            default => $query->orderBy('created_at', 'desc'), // newest
        };

        // ✅ Get filter options
        $categories = Category::active()->get();
        $cities = auth()->user()->listings()->distinct()->pluck('city');

        return $this->view([
            'listings' => $query->paginate(10),
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }
};
?>

<div>
    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Keyword Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search listings..."
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select wire:model.live="category_id"
                        wire:key="category-filter-{{ $category_id }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status"
                        wire:key="status-filter-{{ $status }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select wire:model.live="sort"
                        wire:key="sort-filter-{{ $sort }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="price_asc">Price: Low to High</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="title">Title A-Z</option>
                </select>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="border-t pt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <select wire:model.live="city"
                            wire:key="city-filter-{{ $city }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Cities</option>
                        @foreach($cities as $cityOption)
                            <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Price Range: ${{ $min_price }} - ${{ $max_price }}
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="number" 
                               wire:model.live.debounce.500ms="min_price"
                               min="0"
                               placeholder="Min"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="text-gray-500">to</span>
                        <input type="number" 
                               wire:model.live.debounce.500ms="max_price"
                               min="0"
                               placeholder="Max"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" 
                    wire:click="clearFilters"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="text-center py-4 mb-4">
        <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-md">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading listings...
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-4 text-sm text-gray-600">
        Showing {{ $listings->firstItem() ?? 0 }} to {{ $listings->lastItem() ?? 0 }} of {{ $listings->total() }} listings
    </div>

    <!-- Listings -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" wire:loading.remove>
        <div class="p-6">
            @forelse ($listings as $listing)
                <div class="border-b py-4 last:border-b-0" wire:key="listing-{{ $listing->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">{{ $listing->title }}</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $listing->category->name }} | {{ $listing->city }}, {{ $listing->suburb }}
                            </p>
                            <p class="text-gray-700 mt-2">{{ Str::limit($listing->description, 150) }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="text-sm font-semibold">${{ $listing->price_amount }}/{{ $listing->pricing_type }}</span>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($listing->status === 'approved') bg-green-100 text-green-800
                                    @elseif($listing->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($listing->status === 'draft') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($listing->status) }}
                                    @if($listing->status === 'draft')
                                        (Not submitted)
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="ml-4 flex space-x-2">
                            <a href="{{ route('provider.listings.show', $listing) }}" 
                               class="text-blue-600 hover:text-blue-800">View</a>
                            <a href="{{ route('provider.listings.edit', $listing) }}" 
                               class="text-yellow-600 hover:text-yellow-800">Edit</a>
                            <button type="button"
                                    wire:click="deleteListing({{ $listing->id }})"
                                    wire:confirm="Are you sure you want to delete this listing?"
                                    class="text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    @if($search || $category_id || $status || $city || $min_price > 0 || $max_price < 10000)
                        <p class="text-gray-500 mb-4">No listings match your filters.</p>
                        <button wire:click="clearFilters" class="text-blue-600 hover:underline">Clear filters</button>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            You haven't created any listings yet. 
                            <a href="{{ route('provider.listings.create') }}" class="text-blue-600 hover:underline">Create your first listing</a>
                        </p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $listings->links() }}
    </div>
</div>