<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Enquiries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filters Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <form action="{{ route('provider.enquiries.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Search') }}
                            </label>
                            <input type="text" 
                                   name="search"
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('Search by subject, message, or customer name...') }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Status') }}
                            </label>
                            <select name="status"
                                    id="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="open" @selected(request('status') == 'open')>{{ __('Open') }}</option>
                                <option value="replied" @selected(request('status') == 'replied')>{{ __('Replied') }}</option>
                                <option value="closed" @selected(request('status') == 'closed')>{{ __('Closed') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-4">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('provider.enquiries.index') }}"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                            {{ __('Clear Filters') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Enquiries List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if($enquiries->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($enquiries as $enquiry)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $enquiry->subject }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($enquiry->status === 'open') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($enquiry->status === 'replied') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst($enquiry->status) }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            <span class="font-medium">From:</span> {{ $enquiry->customer->name }}
                                        </p>

                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            <span class="font-medium">Listing:</span> {{ $enquiry->listing->title }}
                                        </p>

                                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">
                                            {{ $enquiry->message }}
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            {{ $enquiry->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="ml-4">
                                        <a href="{{ route('provider.enquiries.show', $enquiry) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                            {{ __('View & Reply') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($enquiries->hasPages())
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            {{ $enquiries->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No enquiries found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if(request('search') || request('status'))
                                {{ __('No enquiries match your search criteria.') }}
                            @else
                                {{ __('You haven\'t received any enquiries yet.') }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>