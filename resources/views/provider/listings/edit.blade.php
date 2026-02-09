<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Warning -->
                    @if($listing->status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded mb-4">
                            ℹ️ This listing is pending admin approval. You can still edit it.
                        </div>
                    @elseif($listing->status === 'approved')
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">
                            ✅ This listing is live and approved.
                        </div>
                    @elseif($listing->status === 'suspended')
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                            ⚠️ This listing has been suspended by an admin. Contact support for details.
                        </div>
                    @endif

                    <form action="{{ route('provider.listings.update', $listing) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $listing->title) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="category_id" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required>{{ old('description', $listing->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City & Suburb -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="city" 
                                       id="city" 
                                       value="{{ old('city', $listing->city) }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="suburb" class="block text-sm font-medium text-gray-700 mb-2">
                                    Suburb <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="suburb" 
                                       id="suburb" 
                                       value="{{ old('suburb', $listing->suburb) }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('suburb')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="pricing_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pricing Type <span class="text-red-500">*</span>
                                </label>
                                <select name="pricing_type" 
                                        id="pricing_type" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    <option value="hourly" {{ old('pricing_type', $listing->pricing_type) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                    <option value="fixed" {{ old('pricing_type', $listing->pricing_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                                @error('pricing_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Price ($) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="price_amount" 
                                       id="price_amount" 
                                       value="{{ old('price_amount', $listing->price_amount) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('price_amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons (Context-Aware) -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('provider.listings.index') }}">
                                <x-secondary-button type="button">
                                    Cancel
                                </x-secondary-button>
                            </a>

                            @if($listing->status === 'draft')
                                <!-- Draft → Can save or submit -->
                                <x-secondary-button type="submit" name="action" value="draft">
                                    💾 Save as Draft
                                </x-secondary-button>
                                <x-primary-button type="submit" name="action" value="submit">
                                    ✅ Submit for Approval
                                </x-primary-button>
                            @else
                                <!-- Pending/Approved/Suspended → Only "Update" -->
                                <x-primary-button type="submit">
                                    Update Listing
                                </x-primary-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>