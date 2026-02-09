{{-- filepath: resources/views/admin/listings/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin: Manage Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('admin.listings.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search listings..."
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="{{ route('admin.listings.index') }}" class="px-4 py-2 bg-gray-300 rounded">Clear</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Apply Filters</button>
                    </div>
                </form>
            </div>

            <div class="mb-4 text-sm text-gray-600">
                Showing {{ $listings->firstItem() ?? 0 }} to {{ $listings->lastItem() ?? 0 }} of {{ $listings->total() }} listings
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                <tr>
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
                                            <form action="{{ route('admin.listings.approve', $listing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:underline">Approve</button>
                                            </form>
                                        @endif

                                        @if(in_array($listing->status, ['approved', 'pending']))
                                            <form action="{{ route('admin.listings.suspend', $listing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-orange-600 hover:underline">Suspend</button>
                                            </form>
                                        @endif

                                        @if($listing->status === 'suspended')
                                            <form action="{{ route('admin.listings.restore', $listing) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-600 hover:underline">Restore</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No listings found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4">
                    {{ $listings->links() }}
                </div>
            </div> --}}
            <livewire:admin.listing-index />
        </div>
    </div>
</x-app-layout>