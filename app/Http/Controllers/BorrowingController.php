<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function myBorrowings()
    {
        $user = Auth::user();
        
        $activeBorrowings = Borrowing::where('user_id', $user->id)
            ->where('status', 'ongoing')
            ->with('book')
            ->orderBy('due_date')
            ->get();
        
        $history = Borrowing::where('user_id', $user->id)
            ->where('status', 'returned')
            ->with('book')
            ->orderBy('returned_at', 'desc')
            ->paginate(10);
        
        return view('borrowings.my_borrowings', compact('activeBorrowings', 'history'));
    }

    public function extend($id)
    {
        $borrowing = Borrowing::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if (!$borrowing->canExtend()) {
            return back()->with('error', 'Vous ne pouvez pas prolonger cet emprunt.');
        }
        
        $borrowing->extend(7);
        
        return back()->with('success', 'Emprunt prolongé de 7 jours.');
    }
    
    public function memberBorrow($id)
    {
        $user = Auth::user();
        
        // Récupérer le livre par son ID
        $book = Book::findOrFail($id);
        
        // Vérifier que c'est un membre
        if (!$user->isMember()) {
            return back()->with('error', 'Vous devez être membre pour emprunter.');
        }
        
        // Vérifier si le membre peut emprunter
        if (!$user->canBorrow()) {
            return back()->with('error', 'Vous ne pouvez pas emprunter (amendes > 5000 DJF ou 5 emprunts maximum atteint).');
        }
        
        // Vérifier si le livre est disponible
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
        
        // Créer l'emprunt
        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => Carbon::now(),
'due_date' => Carbon::now()->addDays((int) Cache::get('max_borrow_days', 15)),            'status' => 'ongoing',
            'borrowed_by' => $user->id
        ]);
        
        // Diminuer les copies disponibles
        $book->decrementCopies();
        
        // Si réservation du même membre, la convertir
        if ($reservation && $reservation->user_id == $user->id) {
            $reservation->status = 'converted';
            $reservation->save();
        }
        
        return redirect()->route('my.borrowings')
            ->with('success', 'Livre emprunté avec succès ! Date de retour prévue : ' . $borrowing->due_date->format('d/m/Y'));
    }
}