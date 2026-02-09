<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Enquiry Details') }}
            </h2>
            <a href="{{ route('provider.enquiries.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                ← {{ __('Back to Enquiries') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Enquiry Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $enquiry->subject }}
                        </h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($enquiry->status === 'open') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($enquiry->status === 'replied') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endif">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">From:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ $enquiry->customer->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Listing:</span>
                            <a href="{{ route('provider.listings.show', $enquiry->listing) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline ml-2">
                                {{ $enquiry->listing->title }}
                            </a>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Date:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ $enquiry->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Time:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ $enquiry->created_at->format('g:i A') }}</span>
                        </div>
                    </div>

                    @if($enquiry->status !== 'closed')
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <form action="{{ route('provider.enquiries.close', $enquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to close this enquiry?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                    {{ __('Mark as Closed') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conversation Thread -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Conversation') }}</h4>

                    <div class="space-y-4">
                        <!-- Original Enquiry -->
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ substr($enquiry->customer->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $enquiry->customer->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $enquiry->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $enquiry->message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Replies -->
                        @foreach($enquiry->replies as $reply)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full {{ $reply->user_id === auth()->id() ? 'bg-indigo-100 dark:bg-indigo-900' : 'bg-gray-200 dark:bg-gray-700' }} flex items-center justify-center">
                                        <span class="text-sm font-medium {{ $reply->user_id === auth()->id() ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300' }}">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="{{ $reply->user_id === auth()->id() ? 'bg-indigo-50 dark:bg-indigo-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }} rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $reply->user->name }}
                                                @if($reply->user_id === auth()->id())
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">(You)</span>
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->format('M d, Y g:i A') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $reply->message }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Reply Form -->
            @if($enquiry->status !== 'closed')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Send Reply') }}</h4>

                        <form action="{{ route('provider.enquiries.reply', $enquiry) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Your Reply') }}
                                </label>
                                <textarea name="message" 
                                          id="message" 
                                          rows="5" 
                                          required
                                          maxlength="1000"
                                          class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="{{ __('Type your reply here...') }}">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Max 1000 characters') }}</span>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                    {{ __('Send Reply') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-800/50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">{{ __('This enquiry has been closed. No further replies can be sent.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>