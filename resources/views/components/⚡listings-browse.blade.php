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
    public $search = '';

    #[Url]
    public $category_id = '';

    #[Url]
    public $city = '';

    #[Url]
    public $sort = 'newest';

    #[Url]
    public $min_price = 0;

    #[Url]
    public $max_price = 10000;

    // ✅ Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
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
            'city',
            'sort',
            'min_price',
            'max_price',
        ]);
        $this->resetPage();
    }

    public function render()
    {
        // ✅ Build query for approved listings only (public access)
        $query = Listing::with(['category', 'user'])
            ->where('status', 'approved');

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
            'price_low' => $query->orderBy('price_amount', 'asc'),
            'price_high' => $query->orderBy('price_amount', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'), // newest
        };

        // ✅ Get filter options
        $categories = Category::active()->get();
        
        // Get unique cities from approved listings
        $cities = Listing::where('status', 'approved')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return $this->view([
            'listings' => $query->paginate(12),
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }
};
?>

<div>
    <!-- Search & Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Keyword Search -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Search') }}
                </label>
                <input type="text" 
                       id="search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="{{ __('Search listings...') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Category') }}
                </label>
                <select wire:model.live="category_id"
                        id="category"
                        wire:key="category-filter-{{ $category_id }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City Filter -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('City') }}
                </label>
                <select wire:model.live="city"
                        id="city"
                        wire:key="city-filter-{{ $city }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    <option value="">{{ __('All Cities') }}</option>
                    @foreach($cities as $cityOption)
                        <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Min Price -->
            <div>
                <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Min Price') }}
                </label>
                <input type="number" 
                       id="min_price"
                       wire:model.live.debounce.500ms="min_price"
                       min="0"
                       step="10"
                       placeholder="$0"
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
            </div>

            <!-- Max Price -->
            <div>
                <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Max Price') }}
                </label>
                <input type="number" 
                       id="max_price"
                       wire:model.live.debounce.500ms="max_price"
                       min="0"
                       step="10"
                       placeholder="$10000"
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
            </div>

            <!-- Sort -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Sort By') }}
                </label>
                <select wire:model.live="sort"
                        id="sort"
                        wire:key="sort-filter-{{ $sort }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                    <option value="newest">{{ __('Newest First') }}</option>
                    <option value="oldest">{{ __('Oldest First') }}</option>
                    <option value="price_low">{{ __('Price: Low to High') }}</option>
                    <option value="price_high">{{ __('Price: High to Low') }}</option>
                </select>
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-end">
                <button type="button"
                        wire:click="clearFilters"
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    {{ __('Clear Filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-700 dark:text-gray-300">
            {{ __('Showing') }} 
            <span class="font-semibold">{{ $listings->firstItem() ?? 0 }}</span>
            {{ __('to') }}
            <span class="font-semibold">{{ $listings->lastItem() ?? 0 }}</span>
            {{ __('of') }}
            <span class="font-semibold">{{ $listings->total() }}</span>
            {{ __('results') }}
        </p>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="text-center py-4 mb-4">
        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __('Loading...') }}
        </div>
    </div>

    <!-- Listings Grid -->
    @if($listings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:loading.remove>
            @foreach($listings as $listing)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition" wire:key="listing-{{ $listing->id }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $listing->category->name }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $listing->city }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">
                            <a href="{{ route('listings.show', $listing->slug) }}" 
                               class="hover:text-indigo-600 dark:hover:text-indigo-400"
                               wire:navigate>
                                {{ $listing->title }}
                            </a>
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                            {{ Str::limit($listing->description, 120) }}
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ number_format($listing->price_amount, 2) }}
                                <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ $listing->pricing_type }}</span>
                            </span>
                            <a href="{{ route('listings.show', $listing->slug) }}" 
                               class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold"
                               wire:navigate>
                                {{ __('View Details') }} →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $listings->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow" wire:loading.remove>
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No listings found') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your search or filters') }}</p>
            <div class="mt-6">
                <button type="button"
                        wire:click="clearFilters"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Clear all filters') }}
                </button>
            </div>
        </div>
    @endif
</div>