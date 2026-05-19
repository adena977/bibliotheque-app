<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
  public function store(Request $request, Book $book)
{
    $user = Auth::user();
    
    // 1. Vérifier si le membre a déjà emprunté ce livre
    $existingBorrowing = Borrowing::where('user_id', $user->id)
        ->where('book_id', $book->id)
        ->where('status', 'ongoing')
        ->first();
    
    if ($existingBorrowing) {
        return back()->with('error', 'Vous avez déjà emprunté ce livre. Vous ne pouvez pas le réserver.');
    }
    
    // 2. Vérifier si le membre peut réserver (max 3 réservations)
    if (!$user->canReserve()) {
        return back()->with('error', 'Vous avez trop de réservations actives (maximum 3).');
    }
    
    // 3. Vérifier si le membre a déjà une réservation pour ce livre
    $existingReservation = Reservation::where('user_id', $user->id)
        ->where('book_id', $book->id)
        ->where('status', 'pending')
        ->first();
    
    if ($existingReservation) {
        return back()->with('error', 'Vous avez déjà réservé ce livre.');
    }
    
    // 4. Vérifier si le livre est vraiment indisponible
    if ($book->isAvailable()) {
        return back()->with('error', 'Ce livre est disponible. Vous pouvez l\'emprunter directement.');
    }
    
    // 5. Calculer la position dans la file d'attente
    $position = Reservation::where('book_id', $book->id)
        ->where('status', 'pending')
        ->max('position') + 1;
    
    // 6. Créer la réservation
    Reservation::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'reserved_at' => Carbon::now(),
        'expires_at' => Carbon::now()->addDays(3),
        'status' => 'pending',
        'position' => $position ?: 1
    ]);
    
    return redirect()->route('books.show', $book)
        ->with('success', 'Livre réservé avec succès. Position dans la file: ' . ($position ?: 1));
}
    
    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isLibrarian()) {
            abort(403);
        }
        
        $reservation->status = 'cancelled';
        $reservation->save();
        
        return back()->with('success', 'Réservation annulée.');
    }
    
    public function myReservations()
{
    $user = Auth::user();
    
    $reservations = Reservation::where('user_id', $user->id)
        ->where('status', 'pending')
        ->where('expires_at', '>', Carbon::now())
        ->with('book')
        ->orderBy('position')
        ->get();
    
    // 👇 AJOUTE CETTE LIGNE POUR L'HISTORIQUE
    $history = Reservation::where('user_id', $user->id)
        ->whereIn('status', ['expired', 'cancelled', 'converted'])
        ->with('book')
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    // 👇 VERIFIE QUE LES DEUX VARIABLES SONT PASSÉES
    return view('reservations.index', compact('reservations', 'history'));
}
    
}