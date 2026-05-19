<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
   

    public function index()
    {
        $books = Book::with('categories')->orderBy('title')->paginate(15);
        return view('librarian.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('librarian.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books|max:20',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:1',
            'replacement_price' => 'required|integer|min:1000',
            'location' => 'nullable|string|max:100',
            'categories' => 'array'
        ]);
        
        $validated['available_copies'] = $validated['total_copies'];
        $validated['is_available'] = $validated['total_copies'] > 0;
        
        $book = Book::create($validated);
        
        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }
        
        return redirect()->route('librarian.books.index')
            ->with('success', 'Livre ajouté avec succès.');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('librarian.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'total_copies' => 'required|integer|min:1',
            'replacement_price' => 'required|integer|min:1000',
            'location' => 'nullable|string|max:100',
            'categories' => 'array'
        ]);
        
        $diff = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = $book->available_copies + $diff;
        $validated['is_available'] = $validated['available_copies'] > 0;
        
        $book->update($validated);
        
        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }
        
        return redirect()->route('librarian.books.index')
            ->with('success', 'Livre modifié avec succès.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeBorrowings()->exists()) {
            return back()->with('error', 'Ce livre est actuellement emprunté.');
        }
        
        $book->delete();
        
        return redirect()->route('librarian.books.index')
            ->with('success', 'Livre supprimé avec succès.');
    }
}