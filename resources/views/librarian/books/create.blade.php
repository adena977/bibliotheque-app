@extends('layouts.app')

@section('title', 'Ajouter un livre')

@section('header', '➕ Ajouter un livre')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('librarian.books.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Titre *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Auteur *</label>
                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                    @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>ISBN *</label>
                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn') }}" required>
                    @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label>Éditeur</label>
                    <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label>Année publication</label>
                    <input type="number" name="publication_year" class="form-control" value="{{ old('publication_year') }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Nombre d'exemplaires *</label>
                    <input type="number" name="total_copies" class="form-control @error('total_copies') is-invalid @enderror" value="{{ old('total_copies', 1) }}" required min="1">
                    @error('total_copies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Prix remplacement (DJF) *</label>
                    <input type="number" name="replacement_price" class="form-control @error('replacement_price') is-invalid @enderror" value="{{ old('replacement_price', 5000) }}" required min="1000">
                    @error('replacement_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Pages</label>
                    <input type="number" name="pages" class="form-control" value="{{ old('pages') }}" min="1">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Emplacement</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Étagère A1">
                </div>
            </div>
            
            <div class="mb-3">
                <label>Catégories</label>
                <select name="categories[]" class="form-select" multiple>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Ctrl+clic pour sélectionner plusieurs</small>
            </div>
            
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('librarian.books.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection