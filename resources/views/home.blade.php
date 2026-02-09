<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Service Marketplace</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
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
                                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                    {{ __('Home') }}
                                </a>
                                <a href="{{ route('listings.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
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

            <!-- Hero Section -->
            <div class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">
                            {{ __('Find Service Providers') }}
                        </h1>
                        <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                            {{ __('Connect with trusted professionals for all your service needs') }}
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('listings.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                {{ __('Browse All Listings') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            @if($categories->count() > 0)
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    {{ __('Browse by Category') }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                    <a href="{{ route('listings.index', ['category' => $category->id]) }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition text-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $category->listings_count }} {{ __('listings') }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Featured Listings -->
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    {{ __('Latest Listings') }}
                </h2>
                
                @if($listings->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($listings as $listing)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $listing->category->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $listing->city }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    <a href="{{ route('listings.show', $listing->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $listing->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                    {{ Str::limit($listing->description, 120) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                        ${{ number_format($listing->price, 2) }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ $listing->pricing_type }}</span>
                                    </span>
                                    <a href="{{ route('listings.show', $listing->slug) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-semibold">
                                        {{ __('View Details') }} →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-600 dark:text-gray-400 py-12">
                        {{ __('No listings available at the moment.') }}
                    </p>
                @endif
            </div>
        </div>
    </body>
</html>