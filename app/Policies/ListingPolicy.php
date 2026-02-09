<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    /**
     * Anyone (including guests) can view approved listings
     */
    public function view(?User $user, Listing $listing): bool
    {
        // Approved listings are public
        if ($listing->status === 'approved') {
            return true;
        }

        // Owners can view their own listings (any status)
        return $user && $user->id === $listing->user_id;
    }

    /**
     * Only providers can create listings
     */
    public function create(User $user): bool
    {
        return $user->hasRole('provider');
    }

    /**
     * Only the owner can update their listing
     */
    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    /**
     * Only the owner can delete their listing
     */
    public function delete(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    /**
     * Only admins can approve listings (NOT providers)
     */
    public function approve(User $user, Listing $listing): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Only admins can suspend listings
     */
    public function suspend(User $user, Listing $listing): bool
    {
        return $user->hasRole('admin');
    }
}