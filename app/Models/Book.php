<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'publication_year',
        'pages',
        'description',
        'cover_image',
        'total_copies',
        'available_copies',
        'is_available',
        'location',
        'replacement_price'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'pages' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
        'is_available' => 'boolean',
        'replacement_price' => 'integer'
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

    // 👇 AJOUTE CETTE RELATION 👇
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function activeBorrowings()
    {
        return $this->borrowings()->where('status', 'ongoing');
    }

    // Méthodes
    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->is_available;
    }

    public function decrementCopies(): void
    {
        $this->available_copies--;
        if ($this->available_copies <= 0) {
            $this->is_available = false;
        }
        $this->save();
    }

    public function incrementCopies(): void
    {
        $this->available_copies++;
        if ($this->available_copies > 0) {
            $this->is_available = true;
        }
        if ($this->available_copies > $this->total_copies) {
            $this->available_copies = $this->total_copies;
        }
        $this->save();
    }

    public function hasReservation()
    {
        return $this->reservations()->where('status', 'pending')->exists();
    }

    public function getNextReservation()
    {
        return $this->reservations()
            ->where('status', 'pending')
            ->orderBy('position')
            ->first();
    }
}