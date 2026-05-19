@extends('layouts.app')

@section('title', 'Détail de l\'emprunt')

@section('header', '📄 Détail de l\'emprunt #' . $borrowing->id)

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5>Informations du livre</h5>
            </div>
            <div class="card-body">
                <p><strong>Titre :</strong> {{ $borrowing->book->title }}</p>
                <p><strong>Auteur :</strong> {{ $borrowing->book->author }}</p>
                <p><strong>ISBN :</strong> {{ $borrowing->book->isbn }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5>Informations du membre</h5>
            </div>
            <div class="card-body">
                <p><strong>Nom :</strong> {{ $borrowing->user->name }}</p>
                <p><strong>Email :</strong> {{ $borrowing->user->email }}</p>
                <p><strong>Téléphone :</strong> {{ $borrowing->user->phone ?? 'Non renseigné' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5>Détails de l'emprunt</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <p><strong>Date d'emprunt :</strong><br> {{ $borrowing->borrowed_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Date retour prévue :</strong><br> {{ $borrowing->due_date->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Statut :</strong><br>
                    @if($borrowing->status == 'ongoing')
                        <span class="badge bg-success">En cours</span>
                    @elseif($borrowing->status == 'returned')
                        <span class="badge bg-secondary">Retourné</span>
                    @endif
                </p>
            </div>
        </div>
        
        @if($borrowing->returned_at)
        <hr>
        <div class="row">
            <div class="col-md-4">
                <p><strong>Date retour réelle :</strong><br> {{ $borrowing->returned_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Amende :</strong><br> {{ number_format($borrowing->fine, 0, ',', ' ') }} DJF</p>
            </div>
            <div class="col-md-4">
                <p><strong>Statut amende :</strong><br>
                    @if($borrowing->fine_paid)
                        <span class="badge bg-success">Payée</span>
                    @else
                        <span class="badge bg-danger">Impayée</span>
                    @endif
                </p>
            </div>
        </div>
        @endif
        
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Enregistré par :</strong><br> {{ $borrowing->borrowedBy->name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Retour enregistré par :</strong><br> {{ $borrowing->returnedBy->name ?? 'Non traité' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('librarian.borrowings.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>
@endsection