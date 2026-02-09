{{-- filepath: resources/views/provider/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Provider Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Total Listings</div>
                    <div class="text-3xl font-bold">{{ $stats['total_listings'] }}</div>
                </div>
                
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Pending Approval</div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending_listings'] }}</div>
                </div>
                
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Approved</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['approved_listings'] }}</div>
                </div>
                
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm">Draft Listings</div>
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['draft_listings'] }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-x-4">
                    <a href="{{ route('provider.listings.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create New Listing
                    </a>
                    <a href="{{ route('provider.listings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        View All Listings
                    </a>
                    {{-- Remove or comment out until enquiries are implemented --}}
                    {{-- <a href="{{ route('provider.enquiries.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        View Enquiries
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>