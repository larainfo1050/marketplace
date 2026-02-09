<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display customer dashboard
     */
    public function index()
    {
        $stats = [
            'total_enquiries' => auth()->user()->enquiries()->count(),
            'pending_enquiries' => auth()->user()->enquiries()->where('status', 'open')->count(),
            'responded_enquiries' => auth()->user()->enquiries()->where('status', 'replied')->count(),
        ];
        
        return view('dashboard', compact('stats'));
    }
}