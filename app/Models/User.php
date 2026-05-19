<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
        'membership_date',
        'total_fine',
        'total_borrowed'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'membership_date' => 'date',
        'total_fine' => 'integer',
        'total_borrowed' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relations
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Méthodes de rôle
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isLibrarian(): bool
    {
        return $this->role === 'librarian' || $this->isAdmin();
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    // 👇 AJOUTE CES MÉTHODES MANQUANTES 👇
    
    public function getActiveBorrowings()
    {
        return $this->borrowings()
            ->where('status', 'ongoing')
            ->with('book')
            ->get();
    }

    public function getOverdueBorrowings()
    {
        return $this->borrowings()
            ->where('status', 'ongoing')
            ->where('due_date', '<', Carbon::now())
            ->with('book')
            ->get();
    }

    public function getPendingFines()
    {
        return $this->fines()
            ->whereIn('status', ['pending', 'partially_paid'])
            ->get();
    }
// Vérifier si un membre a déjà emprunté un livre spécifique
public function hasBorrowed(Book $book): bool
{
    return $this->borrowings()
        ->where('book_id', $book->id)
        ->where('status', 'ongoing')
        ->exists();
}

// Vérifier si un membre a déjà réservé un livre spécifique
public function hasReserved(Book $book): bool
{
    return $this->reservations()
        ->where('book_id', $book->id)
        ->where('status', 'pending')
        ->exists();
}
    public function canBorrow(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->total_fine >= 5000) {
            return false;
        }
        
        $activeBorrowings = $this->borrowings()
            ->where('status', 'ongoing')
            ->count();
            
        return $activeBorrowings < 5;
    }

    public function canReserve(): bool
    {
        $activeReservations = $this->reservations()
            ->where('status', 'pending')
            ->count();
            
        return $activeReservations < 3;
    }

    public function hasOverdueBooks(): bool
    {
        return $this->borrowings()
            ->where('status', 'ongoing')
            ->where('due_date', '<', Carbon::now())
            ->exists();
    }
}