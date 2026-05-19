@extends('layouts.app')

@section('title', 'Ajouter un utilisateur')

@section('header', '➕ Ajouter un utilisateur')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nom complet *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Rôle *</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>👤 Membre</option>
                        <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>📚 Bibliothécaire</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Administrateur</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Mot de passe *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label>Adresse</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                <label class="form-check-label">Activer le compte immédiatement</label>
            </div>
            
            <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection