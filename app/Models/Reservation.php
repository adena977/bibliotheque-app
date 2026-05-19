<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'reserved_at',
        'expires_at',
        'status',
        'position',
        'notified'
    ];

    protected $casts = [
        'reserved_at' => 'date',
        'expires_at' => 'date',
        'position' => 'integer',
        'notified' => 'boolean'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Méthodes
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }

    public function markAsExpired(): void
    {
        $this->status = 'expired';
        $this->save();
        
        // Recalculer les positions des autres réservations
        $this->book->reservations()
            ->where('status', 'pending')
            ->where('position', '>', $this->position)
            ->decrement('position');
    }

    public function convertToBorrowing($borrowedByUserId = null): ?Borrowing
    {
        if ($this->status !== 'pending' || $this->isExpired()) {
            return null;
        }
        
        // Vérifier la disponibilité du livre
        if (!$this->book->isAvailable()) {
            return null;
        }
        
        // Créer l'emprunt
        $borrowing = Borrowing::create([
            'user_id' => $this->user_id,
            'book_id' => $this->book_id,
            'borrowed_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(15),
            'status' => 'ongoing',
            'borrowed_by' => $borrowedByUserId
        ]);
        
        // Décrémenter les copies disponibles
        $this->book->decrementCopies();
        
        // Marquer la réservation comme convertie
        $this->status = 'converted';
        $this->save();
        
        // Supprimer la notification associée
        $this->user->notifications()
            ->where('type', 'info')
            ->where('data->reservation_id', $this->id)
            ->delete();
        
        return $borrowing;
    }
}