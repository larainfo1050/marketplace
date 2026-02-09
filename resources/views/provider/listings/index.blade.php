<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Listings') }}
            </h2>
            <a href="{{ route('provider.listings.create') }}">
                <x-primary-button>
                    Create New Listing
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse ($listings as $listing)
                        <div class="border-b py-4 last:border-b-0">
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
                                    <form action="{{ route('provider.listings.destroy', $listing) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">
                            You haven't created any listings yet. 
                            <a href="{{ route('provider.listings.create') }}" class="text-blue-600 hover:underline">Create your first listing</a>
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $listings->links() }}
            </div> --}}
            <livewire:provider.listing-index />
        </div>
    </div>
</x-app-layout>