<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('is_available', true);
            } elseif ($request->availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }
        
        $sort = $request->get('sort', 'title');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);
        
        $books = $query->paginate(12);
        $categories = Category::all();
        
        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load(['categories', 'activeBorrowings.user']);
        
        $reservations = $book->reservations()
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('position')
            ->with('user')
            ->get();
        
        $similarBooks = Book::where('author', $book->author)
            ->where('id', '!=', $book->id)
            ->limit(4)
            ->get();
        
        return view('books.show', compact('book', 'reservations', 'similarBooks'));
    }
}