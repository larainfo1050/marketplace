<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Listing;
use App\Http\Requests\StoreEnquiryRequest;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Store a new enquiry
     */
    public function store(StoreEnquiryRequest $request)
    {
        $listing = Listing::findOrFail($request->listing_id);

        // Create enquiry
        Enquiry::create([
            'listing_id' => $listing->id,
            'customer_id' => auth()->id(),
            'provider_id' => $listing->user_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);

        // Clear CAPTCHA from session
        session()->forget(['captcha_num1', 'captcha_num2']);

        return redirect()
            ->route('listings.show', $listing->slug)
            ->with('success', 'Your enquiry has been sent successfully!');
    }

    /**
     * Generate CAPTCHA numbers
     */
    public function generateCaptcha()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        
        session(['captcha_num1' => $num1, 'captcha_num2' => $num2]);
        
        return response()->json([
            'num1' => $num1,
            'num2' => $num2,
        ]);
    }
}