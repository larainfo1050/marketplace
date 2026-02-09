{{-- filepath: resources/views/admin/listings/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin: Listing Details') }}
            </h2>
            <a href="{{ route('admin.listings.index') }}" class="text-blue-600 hover:underline">← Back to Listings</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Status Badge --}}
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

                {{-- Title --}}
                <h1 class="text-3xl font-bold mb-4">{{ $listing->title }}</h1>

                {{-- Details Grid --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Provider</p>
                        <p class="font-semibold">{{ $listing->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $listing->user->email }}</p>
                    </div>

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
                        <p class="text-sm text-gray-600">Created At</p>
                        <p class="font-semibold">{{ $listing->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="font-semibold">{{ $listing->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-2">Description</p>
                    <div class="prose max-w-none">
                        {{ $listing->description }}
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex space-x-4 border-t pt-4">
                    @if($listing->status === 'pending')
                        <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                ✓ Approve
                            </button>
                        </form>
                    @endif

                    @if(in_array($listing->status, ['approved', 'pending']))
                        <form action="{{ route('admin.listings.suspend', $listing) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">
                                ⊘ Suspend
                            </button>
                        </form>
                    @endif

                    @if($listing->status === 'suspended')
                        <form action="{{ route('admin.listings.restore', $listing) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                ↻ Restore to Pending
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.listings.edit', $listing) }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        ✎ Edit
                    </a>

                    <form action="{{ route('admin.listings.destroy', $listing) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            🗑 Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>