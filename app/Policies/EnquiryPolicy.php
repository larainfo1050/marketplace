<?php

namespace App\Policies;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnquiryPolicy
{
   /**
     * Only customers can create enquiries
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Users can view enquiries they're involved in
     */
    public function view(User $user, Enquiry $enquiry): bool
    {
        return $user->id === $enquiry->customer_id 
            || $user->id === $enquiry->provider_id;
    }

    /**
     * Only the provider can reply to enquiries
     */
    public function reply(User $user, Enquiry $enquiry): bool
    {
        return $user->id === $enquiry->provider_id;
    }
}
