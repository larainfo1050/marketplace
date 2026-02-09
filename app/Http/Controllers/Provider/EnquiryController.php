<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryReply;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Display all enquiries for the provider
     */
    public function index(Request $request)
    {
        $query = auth()->user()->receivedEnquiries()
            ->with(['listing', 'customer']);

        // Get filter values from the request
        $search = $request->input('search');
        $status = $request->input('status');

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        return view('provider.enquiries.index', compact('enquiries'));
    }

    /**
     * Display single enquiry with replies
     */
    public function show(Enquiry $enquiry)
    {
        // Ensure this provider owns the listing
        if ($enquiry->provider_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this enquiry.');
        }

        $enquiry->load(['listing', 'customer', 'replies.user']);
        
        return view('provider.enquiries.show', compact('enquiry'));
    }

    /**
     * Store a reply to an enquiry
     */
    public function reply(Request $request, Enquiry $enquiry)
    {
        // Ensure this provider owns the listing
        if ($enquiry->provider_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this enquiry.');
        }

        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        EnquiryReply::create([
            'enquiry_id' => $enquiry->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Update enquiry status to 'replied' if it was 'open'
        if ($enquiry->status === 'open') {
            $enquiry->update(['status' => 'replied']);
        }

        return redirect()
            ->route('provider.enquiries.show', $enquiry)
            ->with('success', 'Reply sent successfully!');
    }

    /**
     * Mark enquiry as closed
     */
    public function close(Enquiry $enquiry)
    {
        // Ensure this provider owns the listing
        if ($enquiry->provider_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this enquiry.');
        }

        $enquiry->update(['status' => 'closed']);

        return redirect()
            ->route('provider.enquiries.index')
            ->with('success', 'Enquiry marked as closed.');
    }
}