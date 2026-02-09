<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $listing->title }} - {{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="enquiryModal()">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                </a>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 transition">
                                    {{ __('Home') }}
                                </a>
                                <a href="{{ route('listings.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 transition">
                                    {{ __('Browse Listings') }}
                                </a>
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">Log in</a>
                                <a href="{{ route('register') }}" class="ms-4 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Success Message -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400 p-4">
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
                </div>
            @endif

            <!-- Listing Detail -->
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-6 text-sm">
                    <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">Home</a>
                    <span class="text-gray-500 dark:text-gray-400 mx-2">/</span>
                    <a href="{{ route('listings.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">Listings</a>
                    <span class="text-gray-500 dark:text-gray-400 mx-2">/</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $listing->title }}</span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                            <!-- Header -->
                            <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        {{ $listing->category->name }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $listing->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $listing->title }}
                                </h1>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $listing->suburb }}, {{ $listing->city }}
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="p-8">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Description</h2>
                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $listing->description }}</p>
                                </div>
                            </div>

                            <!-- Additional Details -->
                            <div class="p-8 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Details</h2>
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pricing Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $listing->pricing_type }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $listing->category->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $listing->suburb }}, {{ $listing->city }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Price Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                            <div class="text-center mb-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Starting from</p>
                                <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">
                                    ${{ number_format($listing->price_amount, 2) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">per {{ $listing->pricing_type }}</p>
                            </div>

                            @auth
                                @if(auth()->user()->hasRole('customer'))
                                    <button @click="openModal" class="block w-full bg-indigo-600 text-white text-center px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
                                        {{ __('Send Enquiry') }}
                                    </button>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                        {{ __('Only customers can send enquiries') }}
                                    </p>
                                @endif
                            @else
                                <div class="space-y-3">
                                    <a href="{{ route('login') }}" class="block w-full bg-indigo-600 text-white text-center px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
                                        {{ __('Login to Send Enquiry') }}
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                        {{ __('Don\'t have an account?') }}
                                        <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Sign up') }}</a>
                                    </p>
                                </div>
                            @endauth
                        </div>

                        <!-- Provider Info -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Provider Information</h3>
                            <div class="flex items-center mb-4">
                                <div class="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-lg">
                                    {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $listing->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Service Provider</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Member since') }} {{ $listing->user->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enquiry Modal -->
        <div x-show="showModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="closeModal"
                     aria-hidden="true"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form action="{{ route('enquiries.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                        {{ __('Send Enquiry') }}
                                    </h3>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Send a message to') }} {{ $listing->user->name }}
                                    </p>
                                    
                                    <div class="mt-4 space-y-4">
                                        <!-- Subject -->
                                        <div>
                                            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Subject') }}
                                            </label>
                                            <input type="text" 
                                                   name="subject" 
                                                   id="subject" 
                                                   required
                                                   maxlength="255"
                                                   value="{{ old('subject') }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('subject')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Message -->
                                        <div>
                                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Message') }}
                                            </label>
                                            <textarea name="message" 
                                                      id="message" 
                                                      rows="4" 
                                                      required
                                                      maxlength="1000"
                                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                                            @error('message')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- CAPTCHA -->
                                        <div>
                                            <label for="captcha_answer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Verify you are human') }}
                                            </label>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ __('What is') }} <span x-text="captcha.num1" class="font-bold"></span> + <span x-text="captcha.num2" class="font-bold"></span>?
                                            </p>
                                            <input type="number" 
                                                   name="captcha_answer" 
                                                   id="captcha_answer" 
                                                   required
                                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('captcha_answer')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Send Enquiry') }}
                            </button>
                            <button type="button" 
                                    @click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function enquiryModal() {
                return {
                    showModal: false,
                    captcha: {
                        num1: 0,
                        num2: 0
                    },
                    
                    async openModal() {
                        // Generate CAPTCHA
                        try {
                            const response = await fetch('{{ route('enquiries.captcha') }}');
                            const data = await response.json();
                            this.captcha = data;
                            this.showModal = true;
                        } catch (error) {
                            console.error('Failed to load CAPTCHA:', error);
                        }
                    },
                    
                    closeModal() {
                        this.showModal = false;
                    }
                }
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>