<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display provider dashboard
     */
    public function index()
    {
        $stats = [
            'total_listings' => auth()->user()->listings()->count(),
            'pending_listings' => auth()->user()->listings()->where('status', 'pending')->count(),
            'approved_listings' => auth()->user()->listings()->where('status', 'approved')->count(),
            'draft_listings' => auth()->user()->listings()->where('status', 'draft')->count(),
            'total_enquiries' => auth()->user()->receivedEnquiries()->count(),
            'open_enquiries' => auth()->user()->receivedEnquiries()->where('status', 'open')->count(),
        ];
        
        return view('provider.dashboard', compact('stats'));
    }
}