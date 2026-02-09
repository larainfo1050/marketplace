{{-- filepath: resources/views/components/admin/⚡listing-index.blade.php --}}
<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Listing;
use App\Models\Category;

new class extends Component {
    use WithPagination;

    // ✅ URL query parameters (shareable links)
    #[Url(as: 'q')]
    public $search = '';  // ✅ Remove type hint

    #[Url]
    public $category_id = '';  // ✅ Remove type hint

    #[Url]
    public $status = '';  // ✅ Remove type hint

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

    /**
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->reset(['search', 'category_id', 'status']);
        $this->resetPage();
    }

    /**
     * Approve a listing
     */
    public function approveListing($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        if ($listing->status !== 'pending') {
            session()->flash('error', 'Only pending listings can be approved.');
            return;
        }

        $listing->update(['status' => 'approved']);
        session()->flash('success', 'Listing approved successfully!');
    }

    /**
     * Suspend a listing
     */
    public function suspendListing($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        if (!in_array($listing->status, ['approved', 'pending'])) {
            session()->flash('error', 'Cannot suspend this listing.');
            return;
        }

        $listing->update(['status' => 'suspended']);
        session()->flash('success', 'Listing suspended!');
    }

    /**
     * Restore a listing
     */
    public function restoreListing($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        if ($listing->status !== 'suspended') {
            session()->flash('error', 'Only suspended listings can be restored.');
            return;
        }

        $listing->update(['status' => 'pending']);
        session()->flash('success', 'Listing restored to pending!');
    }

    /**
     * Delete a listing
     */
    public function deleteListing($listingId)
    {
        $listing = Listing::findOrFail($listingId);
        $listing->delete();
        session()->flash('success', 'Listing deleted successfully!');
    }

    public function render()
    {
        // ✅ Admin sees ALL listings
        $query = Listing::with(['category', 'user']);

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

        // ✅ Sort by newest
        $query->latest();

        $categories = Category::active()->get();

        return $this->view([
            'listings' => $query->paginate(20),
            'categories' => $categories,
        ]);
    }
};
?>

<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search listings..."
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
        </div>

        <div class="flex justify-end mt-4">
            <button type="button" 
                    wire:click="clearFilters"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
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

    <!-- Listings Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" wire:loading.remove>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($listings as $listing)
                        <tr wire:key="listing-{{ $listing->id }}">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $listing->title }}</div>
                                <div class="text-sm text-gray-500">{{ $listing->city }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $listing->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $listing->category->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($listing->status === 'approved') bg-green-100 text-green-800
                                    @elseif($listing->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($listing->status === 'draft') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $listing->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('admin.listings.show', $listing) }}" class="text-blue-600 hover:underline">View</a>
                                
                                @if($listing->status === 'pending')
                                    <button wire:click="approveListing({{ $listing->id }})" 
                                            wire:confirm="Approve this listing?"
                                            class="text-green-600 hover:underline">
                                        Approve
                                    </button>
                                @endif

                                @if(in_array($listing->status, ['approved', 'pending']))
                                    <button wire:click="suspendListing({{ $listing->id }})"
                                            wire:confirm="Suspend this listing?"
                                            class="text-orange-600 hover:underline">
                                        Suspend
                                    </button>
                                @endif

                                @if($listing->status === 'suspended')
                                    <button wire:click="restoreListing({{ $listing->id }})"
                                            wire:confirm="Restore this listing to pending?"
                                            class="text-blue-600 hover:underline">
                                        Restore
                                    </button>
                                @endif

                                <button wire:click="deleteListing({{ $listing->id }})"
                                        wire:confirm="Are you sure you want to delete this listing? This cannot be undone."
                                        class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                @if($search || $category_id || $status)
                                    No listings match your filters.
                                    <button wire:click="clearFilters" class="text-blue-600 hover:underline ml-2">Clear filters</button>
                                @else
                                    No listings in the system yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $listings->links() }}
        </div>
    </div>
</div>