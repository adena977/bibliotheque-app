@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('header', '✏️ Modifier l\'utilisateur : ' . $user->name)

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nom complet *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Rôle *</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>👤 Membre</option>
                        <option value="librarian" {{ old('role', $user->role) == 'librarian' ? 'selected' : '' }}>📚 Bibliothécaire</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>👑 Administrateur</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Statut</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <small class="text-muted">Laisser vide pour conserver l'ancien mot de passe</small>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            
            <div class="mb-3">
                <label>Adresse</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Informations supplémentaires :</strong><br>
                - Date d'inscription : {{ $user->membership_date->format('d/m/Y') }}<br>
                - Total des amendes : {{ number_format($user->total_fine, 0, ',', ' ') }} DJF<br>
                - Total emprunts : {{ $user->total_borrowed }}
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>
@endsection