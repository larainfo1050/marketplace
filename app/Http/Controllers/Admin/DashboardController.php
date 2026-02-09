<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        $stats = [
            'total_listings' => Listing::count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'approved_listings' => Listing::where('status', 'approved')->count(),
            'suspended_listings' => Listing::where('status', 'suspended')->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}