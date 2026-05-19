<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'partially_paid'])
            ->with('borrowing.book')
            ->get();
        
        $history = Fine::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->paginate(10);
        
        $totalDue = $fines->sum('amount') - $fines->sum('paid_amount');
        
        return view('fines.index', compact('fines', 'history', 'totalDue'));
    }
}