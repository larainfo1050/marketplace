<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Listings</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_listings'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-yellow-600 text-sm font-medium">Pending Approval</div>
                    <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_listings'] }}</div>
                    <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" 
                       class="text-sm text-yellow-600 hover:underline mt-2 inline-block">
                        Review →
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-green-600 text-sm font-medium">Approved</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ $stats['approved_listings'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-red-600 text-sm font-medium">Suspended</div>
                    <div class="text-3xl font-bold text-red-600 mt-2">{{ $stats['suspended_listings'] }}</div>
                    <a href="{{ route('admin.listings.index', ['status' => 'suspended']) }}" 
                       class="text-sm text-red-600 hover:underline mt-2 inline-block">
                        View →
                    </a>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="flex gap-4">
                    <a href="{{ route('admin.listings.index') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        All Listings
                    </a>
                    <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" 
                       class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        Pending Review ({{ $stats['pending_listings'] }})
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>