<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Enquiries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <form action="{{ route('customer.enquiries.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Filter by Status') }}
                            </label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="open" @selected(request('status') == 'open')>{{ __('Open') }}</option>
                                <option value="replied" @selected(request('status') == 'replied')>{{ __('Replied') }}</option>
                                <option value="closed" @selected(request('status') == 'closed')>{{ __('Closed') }}</option>
                            </select>
                        </div>
                        <div class="mt-4 md:mt-0 md:self-end">
                             <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                {{ __('Filter') }}
                            </button>
                            <a href="{{ route('customer.enquiries.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Enquiries List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @forelse($enquiries as $enquiry)
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
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
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">To:</span> {{ $enquiry->provider->name }}
                                    <span class="mx-2">|</span>
                                    <span class="font-medium">Listing:</span> {{ $enquiry->listing->title }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                    {{ $enquiry->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('customer.enquiries.show', $enquiry) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                    {{ __('View Conversation') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No enquiries found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('You have not sent any enquiries yet.') }}</p>
                    </div>
                @endforelse
            </div>

            @if ($enquiries->hasPages())
                <div class="mt-6">
                    {{ $enquiries->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>