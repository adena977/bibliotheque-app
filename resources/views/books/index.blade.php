@extends('layouts.app')

@section('title', 'Catalogue des livres')

@section('header', '📚 Catalogue des livres')

@section('content')
<div class="row">
    <!-- Filtres -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header">Filtres</div>
            <div class="card-body">
                <form method="GET">
                    <div class="mb-3">
                        <label>Recherche</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Titre, auteur...">
                    </div>
                    
                    <div class="mb-3">
                        <label>Catégorie</label>
                        <select name="category" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Disponibilité</label>
                        <select name="availability" class="form-select">
                            <option value="">Tous</option>
                            <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Indisponible</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Trier par</label>
                        <select name="sort" class="form-select">
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titre</option>
                            <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Auteur</option>
                            <option value="publication_year" {{ request('sort') == 'publication_year' ? 'selected' : '' }}>Année</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    <a href="{{ route('books.index') }}" class="btn btn-secondary w-100 mt-2">Réinitialiser</a>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Liste des livres -->
    <div class="col-md-9">
        <div class="row">
            @forelse($books as $book)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-book fa-2x text-primary mb-2"></i>
                        <h6 class="card-title">{{ Str::limit($book->title, 50) }}</h6>
                        <p class="card-text small text-muted">{{ $book->author }}</p>
                        <div class="mt-2">
                            <span class="badge {{ $book->is_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $book->is_available ? 'Disponible' : 'Indisponible' }}
                            </span>
                            <small class="text-muted ms-2">{{ $book->available_copies }}/{{ $book->total_copies }} exemplaires</small>
                        </div>
                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary mt-3 w-100">Détails</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">Aucun livre trouvé.</div>
            </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $books->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection