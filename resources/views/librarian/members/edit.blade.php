@extends('layouts.app')

@section('title', 'Modifier un membre')

@section('header', '✏️ Modifier le membre : ' . $member->name)

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('librarian.members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nom complet *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Laisser vide pour conserver l'ancien mot de passe</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            
            <div class="mb-3">
                <label>Adresse</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $member->address) }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="{{ route('librarian.members.show', $member) }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection