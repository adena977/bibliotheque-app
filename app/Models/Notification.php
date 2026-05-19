<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'data',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthodes
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = Carbon::now();
            $this->save();
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Méthodes statiques pour créer des notifications
    public static function sendOverdueNotification($user, $borrowing)
    {
        return self::create([
            'user_id' => $user->id,
            'title' => 'Livre en retard',
            'message' => "Le livre '{$borrowing->book->title}' est en retard. Veuillez le retourner au plus vite.",
            'type' => 'warning',
            'data' => ['borrowing_id' => $borrowing->id]
        ]);
    }

    public static function sendBookAvailableNotification($user, $book, $reservation)
    {
        return self::create([
            'user_id' => $user->id,
            'title' => 'Livre disponible',
            'message' => "Le livre '{$book->title}' que vous avez réservé est maintenant disponible. Venez le retirer dans les 48h.",
            'type' => 'success',
            'data' => ['book_id' => $book->id, 'reservation_id' => $reservation->id]
        ]);
    }

    public static function sendFineNotification($user, $fine)
    {
        return self::create([
            'user_id' => $user->id,
            'title' => 'Amende à payer',
            'message' => "Vous avez une amende de {$fine->amount} DJF à régler avant le " . $fine->due_date->format('d/m/Y'),
            'type' => 'error',
            'data' => ['fine_id' => $fine->id]
        ]);
    }
}