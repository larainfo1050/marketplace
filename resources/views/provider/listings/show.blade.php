<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Listing Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('provider.listings.edit', $listing) }}">
                    <x-secondary-button>
                        Edit
                    </x-secondary-button>
                </a>
                <a href="{{ route('provider.listings.index') }}">
                    <x-secondary-button>
                        Back to Listings
                    </x-secondary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Badge -->
                    <div class="mb-4">
                        <span class="px-3 py-1 text-sm rounded-full 
                            @if($listing->status === 'approved') bg-green-100 text-green-800
                            @elseif($listing->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($listing->status === 'draft') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($listing->status) }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold mb-4">{{ $listing->title }}</h1>

                    <!-- Meta Info -->
                    <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
                        <div>
                            <p class="text-sm text-gray-600">Category</p>
                            <p class="font-semibold">{{ $listing->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Location</p>
                            <p class="font-semibold">{{ $listing->city }}, {{ $listing->suburb }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pricing</p>
                            <p class="font-semibold">${{ $listing->price_amount }}/{{ $listing->pricing_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Created</p>
                            <p class="font-semibold">{{ $listing->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <div class="prose max-w-none">
                            {{ $listing->description }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-t pt-4 flex justify-between items-center">
                        <form action="{{ route('provider.listings.destroy', $listing) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <x-secondary-button type="submit" class="!bg-red-600 !text-white hover:!bg-red-700">
                                Delete Listing
                            </x-secondary-button>
                        </form>

                        <a href="{{ route('provider.listings.edit', $listing) }}">
                            <x-primary-button>
                                Edit Listing
                            </x-primary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>