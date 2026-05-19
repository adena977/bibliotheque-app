<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
        'fine',
        'fine_paid',
        'notes',
        'borrowed_by',
        'returned_by'
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
        'fine' => 'integer',
        'fine_paid' => 'boolean'
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

    public function borrowedBy()
    {
        return $this->belongsTo(User::class, 'borrowed_by');
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function fineRecord()
    {
        return $this->hasOne(Fine::class);
    }

    // Méthodes
    public function isOverdue(): bool
    {
        return $this->status === 'ongoing' && Carbon::now()->gt($this->due_date);
    }

    public function calculateFine(): int
    {
        if ($this->returned_at) {
            $returnDate = Carbon::parse($this->returned_at);
            $dueDate = Carbon::parse($this->due_date);
            
            if ($returnDate->gt($dueDate)) {
                $daysLate = $returnDate->diffInDays($dueDate);
                return $daysLate * 50; // 50 DJF par jour
            }
        } elseif ($this->status === 'ongoing' && Carbon::now()->gt($this->due_date)) {
            $daysLate = Carbon::now()->diffInDays($this->due_date);
            return $daysLate * 50;
        }
        
        return 0;
    }

    public function markAsReturned($returnedByUserId = null): void
    {
        $this->returned_at = Carbon::now();
        $this->status = 'returned';
        $this->fine = $this->calculateFine();
        
        if ($returnedByUserId) {
            $this->returned_by = $returnedByUserId;
        }
        
        $this->save();
        
        // Incrémenter les copies disponibles du livre
        $this->book->incrementCopies();
        
        // Créer un enregistrement d'amende si nécessaire
        if ($this->fine > 0) {
            $this->createFineRecord();
        }
    }

    public function createFineRecord(): void
    {
        Fine::create([
            'user_id' => $this->user_id,
            'borrowing_id' => $this->id,
            'amount' => $this->fine,
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(15),
            'reason' => 'Retard de retour'
        ]);
        
        // Mettre à jour le total_fine de l'utilisateur
        $this->user->increment('total_fine', $this->fine);
    }

    public function canExtend(): bool
    {
        // Vérifier si l'emprunt peut être prolongé
        return $this->status === 'ongoing' && 
               !$this->isOverdue() &&
               Carbon::now()->diffInDays($this->due_date) > 2;
    }

    public function extend($days = 7): void
    {
        if ($this->canExtend()) {
            $this->due_date = Carbon::parse($this->due_date)->addDays($days);
            $this->save();
        }
    }
}