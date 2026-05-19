<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrowing_id',
        'amount',
        'paid_amount',
        'status',
        'due_date',
        'paid_at',
        'reason',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_amount' => 'integer',
        'due_date' => 'date',
        'paid_at' => 'date'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Méthodes
    public function getRemainingAmount(): int
    {
        return $this->amount - $this->paid_amount;
    }

    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->amount;
    }

    public function pay($amount): void
    {
        $this->paid_amount += $amount;
        
        if ($this->isFullyPaid()) {
            $this->status = 'paid';
            $this->paid_at = Carbon::now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partially_paid';
        }
        
        $this->save();
        
        // Mettre à jour le total_fine de l'utilisateur
        $this->user->decrement('total_fine', $amount);
        
        // Marquer l'amende comme payée dans l'emprunt
        if ($this->isFullyPaid() && $this->borrowing) {
            $this->borrowing->fine_paid = true;
            $this->borrowing->save();
        }
    }

    public function waive($reason = null): void
    {
        $this->status = 'waived';
        $this->reason = $reason ?: 'Amende annulée par l\'administrateur';
        $this->save();
        
        // Remettre le total_fine de l'utilisateur à zéro pour cette amende
        $remainingAmount = $this->getRemainingAmount();
        if ($remainingAmount > 0) {
            $this->user->decrement('total_fine', $remainingAmount);
        }
        
        if ($this->borrowing) {
            $this->borrowing->fine = 0;
            $this->borrowing->fine_paid = true;
            $this->borrowing->save();
        }
    }
}