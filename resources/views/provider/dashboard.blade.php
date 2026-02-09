<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Provider Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">Total Listings</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_listings'] }}</div>
                </div>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">Pending Approval</div>
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_listings'] }}</div>
                </div>
                
                <div class="bg-green-50 dark:bg-green-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">Approved</div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['approved_listings'] }}</div>
                </div>
                
                <div class="bg-blue-50 dark:bg-blue-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">Draft Listings</div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['draft_listings'] }}</div>
                </div>

                <div class="bg-purple-50 dark:bg-purple-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">Total Enquiries</div>
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total_enquiries'] }}</div>
                </div>

                <div class="bg-orange-50 dark:bg-orange-900/20 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">Open Enquiries</div>
                    <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['open_enquiries'] }}</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('provider.listings.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Listing
                    </a>
                    <a href="{{ route('provider.listings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        View All Listings
                    </a>
                    <a href="{{ route('provider.enquiries.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        View Enquiries
                        @if($stats['open_enquiries'] > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ $stats['open_enquiries'] }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>