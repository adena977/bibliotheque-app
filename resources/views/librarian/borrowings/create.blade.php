@extends('layouts.app')

@section('title', 'Nouvel emprunt')

@section('header', '➕ Nouvel emprunt')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('librarian.borrowings.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Membre *</label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Sélectionner un membre</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Livre *</label>
                    <select name="book_id" class="form-select @error('book_id') is-invalid @enderror" required>
                        <option value="">Sélectionner un livre</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} - {{ $book->author }} ({{ $book->available_copies }} dispo)
                            </option>
                        @endforeach
                    </select>
                    @error('book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Durée d'emprunt : <strong>15 jours</strong><br>
                Amende en cas de retard : <strong>50 DJF par jour</strong>
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer l'emprunt</button>
            <a href="{{ route('librarian.borrowings.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection