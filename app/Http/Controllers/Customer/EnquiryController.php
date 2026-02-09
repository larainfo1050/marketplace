<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Listing;
use App\Http\Requests\StoreEnquiryRequest;
use Illuminate\Http\Request;
use App\Models\EnquiryReply;

class EnquiryController extends Controller
{
    /**
     * Display a list of the customer's enquiries.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->enquiries()
            ->with(['listing', 'provider']);

        $status = $request->input('status');

        if ($status) {
            $query->where('status', $status);
        }

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        return view('customer.enquiries.index', compact('enquiries'));
    }

    /**
     * Display a single enquiry thread.
     */
    public function show(Enquiry $enquiry)
    {
        // Ensure the customer owns this enquiry
        if ($enquiry->customer_id !== auth()->id()) {
            abort(403);
        }

        $enquiry->load(['listing', 'provider', 'replies.user']);

        return view('customer.enquiries.show', compact('enquiry'));
    }

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
     * Store a reply to an enquiry.
     */
    public function reply(Request $request, Enquiry $enquiry)
    {
        // Ensure the customer owns this enquiry
        if ($enquiry->customer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        EnquiryReply::create([
            'enquiry_id' => $enquiry->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Re-open the enquiry for the provider
        $enquiry->update(['status' => 'open']);

        return redirect()
            ->route('customer.enquiries.show', $enquiry)
            ->with('success', 'Reply sent successfully!');
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