<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Enquiry Conversation') }}
            </h2>
            <a href="{{ route('customer.enquiries.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                ← {{ __('Back to My Enquiries') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400 p-4">
                    <p class="text-sm text-green-700 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Enquiry Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $enquiry->subject }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Conversation with <span class="font-medium">{{ $enquiry->provider->name }}</span> regarding the listing <a href="{{ route('listings.show', $enquiry->listing->slug) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $enquiry->listing->title }}</a>.
                </p>
            </div>

            <!-- Conversation Thread -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Conversation') }}</h4>
                <div class="space-y-4">
                    @foreach($enquiry->replies->sortBy('created_at') as $reply)
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
                                            @if($reply->user_id === auth()->id()) (You) @endif
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

            <!-- Reply Form -->
            @if($enquiry->status !== 'closed')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Send a Reply') }}</h4>
                    <form action="{{ route('customer.enquiries.reply', $enquiry) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <textarea name="message" rows="5" required maxlength="1000" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Type your reply..."></textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700">
                                {{ __('Send Reply') }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                 <div class="bg-gray-50 dark:bg-gray-800/50 text-center p-6 rounded-lg">
                    <p class="text-gray-600 dark:text-gray-400">{{ __('This enquiry has been closed by the provider.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>