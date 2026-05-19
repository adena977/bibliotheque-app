<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isLibrarian()) {
            return $this->librarianDashboard();
        }
        
        return $this->memberDashboard();
    }
    private function adminDashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalBooks = Book::sum('total_copies');
        $availableBooks = Book::sum('available_copies');
        $activeBorrowings = Borrowing::where('status', 'ongoing')->count();
        $overdueBorrowings = Borrowing::where('status', 'ongoing')
            ->where('due_date', '<', Carbon::now())
            ->count();
        $totalFines = Borrowing::sum('fine');
        
        $monthlyBorrowings = Borrowing::whereMonth('borrowed_at', Carbon::now()->month)->count();
        
        $topBooks = Borrowing::select('book_id')
            ->with('book')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('book_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('dashboard.admin', compact(
            'totalUsers', 'activeUsers', 'totalBooks', 'availableBooks',
            'activeBorrowings', 'overdueBorrowings', 'totalFines',
            'monthlyBorrowings', 'topBooks', 'recentBorrowings'
        ));
    }

    private function librarianDashboard()
    {
        $today = Carbon::today();
        $weekLater = Carbon::today()->addDays(7);
        
        $booksToReturn = Borrowing::where('status', 'ongoing')
            ->whereBetween('due_date', [$today, $weekLater])
            ->with(['user', 'book'])
            ->orderBy('due_date')
            ->limit(15)
            ->get();
        
        $overdueBooks = Borrowing::where('status', 'ongoing')
            ->where('due_date', '<', $today)
            ->with(['user', 'book'])
            ->orderBy('due_date')
            ->get();
        
        $availableBooks = Book::where('is_available', true)->count();
        $totalBorrowings = Borrowing::whereDate('borrowed_at', $today)->count();
        
        $pendingReservations = Reservation::where('status', 'pending')
            ->where('expires_at', '>', Carbon::now())
            ->with(['user', 'book'])
            ->orderBy('position')
            ->limit(10)
            ->get();
        
        return view('dashboard.librarian', compact(
            'booksToReturn', 'overdueBooks', 'availableBooks',
            'totalBorrowings', 'pendingReservations'
        ));
    }

    private function memberDashboard()
    {
        $user = Auth::user();
        
        $activeBorrowings = $user->getActiveBorrowings();
        $overdueBorrowings = $user->getOverdueBorrowings();
        $pendingFines = $user->getPendingFines();
        $totalFine = $user->total_fine;
        
        $borrowingHistory = Borrowing::where('user_id', $user->id)
            ->where('status', 'returned')
            ->with('book')
            ->orderBy('returned_at', 'desc')
            ->limit(10)
            ->get();
        
        $activeReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', Carbon::now())
            ->with('book')
            ->get();
        
        $recommendedBooks = Book::where('is_available', true)
            ->inRandomOrder()
            ->limit(6)
            ->get();
        
        $unreadNotifications = $user->notifications()
            ->where('is_read', false)
            ->count();
        
        $canBorrow = $user->canBorrow();
        $canReserve = $user->canReserve();
        
        return view('dashboard.member', compact(
            'activeBorrowings', 'overdueBorrowings', 'pendingFines',
            'totalFine', 'borrowingHistory', 'activeReservations',
            'recommendedBooks', 'unreadNotifications', 'canBorrow', 'canReserve'
        ));
    }
}