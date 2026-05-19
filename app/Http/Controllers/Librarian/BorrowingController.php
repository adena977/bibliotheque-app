<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowingController extends Controller
{
   

    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhereHas('book', function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%");
            });
        }
        
        $borrowings = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('librarian.borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->where('is_active', true)->get();
        $books = Book::where('is_available', true)->get();
        
        return view('librarian.borrowings.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);
        
        $user = User::find($validated['user_id']);
        $book = Book::find($validated['book_id']);
        
        if (!$user->canBorrow()) {
            return back()->with('error', 'Cet utilisateur ne peut pas emprunter (amendes ou inactif).');
        }
        
        if (!$book->isAvailable()) {
            return back()->with('error', 'Ce livre n\'est pas disponible.');
        }
        
        // Vérifier les réservations
        $reservation = Reservation::where('book_id', $book->id)
            ->where('status', 'pending')
            ->orderBy('position')
            ->first();
            
        if ($reservation && $reservation->user_id != $user->id) {
            return back()->with('error', 'Ce livre est réservé par un autre membre.');
        }
        
        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(15),
            'status' => 'ongoing',
            'borrowed_by' => Auth::id()
        ]);
        
        $book->decrementCopies();
        
        if ($reservation && $reservation->user_id == $user->id) {
            $reservation->status = 'converted';
            $reservation->save();
        }
        
        return redirect()->route('librarian.borrowings.index')
            ->with('success', 'Emprunt enregistré avec succès.');
    }

    public function return(Book $book, User $user)
    {
        $borrowing = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'ongoing')
            ->firstOrFail();
        
        $borrowing->markAsReturned(Auth::id());
        
        $message = 'Livre retourné avec succès.';
        if ($borrowing->fine > 0) {
            $message .= ' Amende : ' . number_format($borrowing->fine, 0, ',', ' ') . ' DJF';
        }
        
        // Vérifier la prochaine réservation
        $nextReservation = $book->getNextReservation();
        if ($nextReservation) {
            $nextReservation->notified = true;
            $nextReservation->save();
            
            // Notification au prochain réservataire
            \App\Models\Notification::sendBookAvailableNotification($nextReservation->user, $book, $nextReservation);
        }
        
        return redirect()->route('librarian.borrowings.index')
            ->with('success', $message);
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book', 'borrowedBy', 'returnedBy']);
        return view('librarian.borrowings.show', compact('borrowing'));
    }
}