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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation (same as home) -->
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
                                    ${{ number_format($listing->price, 2) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">per {{ $listing->pricing_type }}</p>
                            </div>

                            @auth
                                @if(auth()->user()->hasRole('customer'))
                                    <a href="#" class="block w-full bg-indigo-600 text-white text-center px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
                                        {{ __('Send Enquiry') }}
                                    </a>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                        {{ __('Only customers can send enquiries') }}
                                    </p>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full bg-indigo-600 text-white text-center px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
                                    {{ __('Login to Send Enquiry') }}
                                </a>
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
    </body>
</html>