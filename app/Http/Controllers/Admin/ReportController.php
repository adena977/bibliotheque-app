<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Statistiques générales
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalBooks = Book::sum('total_copies');
        $availableBooks = Book::sum('available_copies');
        $totalBorrowings = Borrowing::count();
        $activeBorrowings = Borrowing::where('status', 'ongoing')->count();
        $completedBorrowings = Borrowing::where('status', 'returned')->count();
        $totalFines = Fine::sum('amount');
        $paidFines = Fine::where('status', 'paid')->sum('amount');
        $pendingFines = $totalFines - $paidFines;
        
        // Statistiques mensuelles des emprunts (12 derniers mois)
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $count = Borrowing::whereYear('borrowed_at', $date->year)
                ->whereMonth('borrowed_at', $date->month)
                ->count();
            $monthlyStats[] = [
                'month' => $monthName,
                'count' => $count
            ];
        }
        
        // Top 10 des livres les plus empruntés
        $topBooks = Borrowing::select('book_id', DB::raw('COUNT(*) as total'))
            ->with('book')
            ->groupBy('book_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Top 10 des membres les plus actifs
        $topMembers = Borrowing::select('user_id', DB::raw('COUNT(*) as total'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        // Statistiques par catégorie
        $categoryStats = DB::table('book_category')
            ->join('categories', 'book_category.category_id', '=', 'categories.id')
            ->join('books', 'book_category.book_id', '=', 'books.id')
            ->select('categories.name', DB::raw('COUNT(DISTINCT books.id) as total_books'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
        
        // Emprunts en retard
        $overdueBorrowings = Borrowing::where('status', 'ongoing')
            ->where('due_date', '<', Carbon::now())
            ->count();
        
        // Taux de retour à l'heure
        $onTimeReturns = Borrowing::where('status', 'returned')
            ->whereRaw('returned_at <= due_date')
            ->count();
        $onTimeRate = $completedBorrowings > 0 ? round(($onTimeReturns / $completedBorrowings) * 100, 2) : 0;
        
        // Revenus des amendes par mois
        $fineRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $amount = Fine::where('status', 'paid')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');
            $fineRevenue[] = [
                'month' => $monthName,
                'amount' => $amount
            ];
        }
        
        return view('admin.reports.index', compact(
            'totalUsers', 'activeUsers', 'totalBooks', 'availableBooks',
            'totalBorrowings', 'activeBorrowings', 'completedBorrowings',
            'totalFines', 'paidFines', 'pendingFines', 'monthlyStats',
            'topBooks', 'topMembers', 'categoryStats', 'overdueBorrowings',
            'onTimeRate', 'fineRevenue'
        ));
    }
    
    public function borrowingStats(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        $monthlyStats = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = Borrowing::whereYear('borrowed_at', $year)
                ->whereMonth('borrowed_at', $month)
                ->count();
            
            $monthlyStats[] = [
                'month' => $month,
                'count' => $count
            ];
        }
        
        $topBooks = Borrowing::select('book_id')
            ->with('book')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('book_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        $topMembers = Borrowing::select('user_id')
            ->with('user')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        $totalFinesCollected = Fine::where('status', 'paid')->sum('amount');
        
        return view('admin.reports.borrowing', compact('monthlyStats', 'topBooks', 'topMembers', 'totalFinesCollected', 'year'));
    }
    
    public function export(Request $request)
    {
        $type = $request->get('type');
        $format = $request->get('format', 'csv');
        
        if ($type === 'books') {
            $data = Book::all();
            $filename = 'livres_' . date('Y-m-d');
        } elseif ($type === 'borrowings') {
            $data = Borrowing::with(['user', 'book'])->get();
            $filename = 'emprunts_' . date('Y-m-d');
        } elseif ($type === 'users') {
            $data = User::all();
            $filename = 'utilisateurs_' . date('Y-m-d');
        } elseif ($type === 'fines') {
            $data = Fine::with(['user', 'borrowing'])->get();
            $filename = 'amendes_' . date('Y-m-d');
        } else {
            return back()->with('error', 'Type de rapport invalide.');
        }
        
        if ($format === 'csv') {
            return $this->exportCsv($data, $filename);
        }
        
        return back()->with('error', 'Format non supporté.');
    }
    
    private function exportCsv($data, $filename)
    {
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                // Headers
                $firstItem = $data->first()->toArray();
                fputcsv($file, array_keys($firstItem), ';');
                
                // Data
                foreach ($data as $row) {
                    fputcsv($file, $row->toArray(), ';');
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
            'Cache-Control' => 'no-cache, must-revalidate'
        ]);
    }
}