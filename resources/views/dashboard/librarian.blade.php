@extends('layouts.app')

@section('title', 'Tableau de bord - Bibliothécaire')

@section('header', '📊 Tableau de bord bibliothécaire')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Livres disponibles</h5>
                <h2>{{ $availableBooks }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Emprunts aujourd'hui</h5>
                <h2>{{ $totalBorrowings }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5>⏰ Livres à rendre cette semaine</h5>
            </div>
            <div class="card-body">
                @if($booksToReturn->isEmpty())
                    <p class="text-muted">Aucun livre à rendre cette semaine.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Livre</th><th>Membre</th><th>Date retour</th></tr>
                            </thead>
                            <tbody>
                                @foreach($booksToReturn as $borrowing)
                                <tr>
                                    <td>{{ $borrowing->book->title }}</td>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ $borrowing->due_date->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5>⚠️ Livres en retard</h5>
            </div>
            <div class="card-body">
                @if($overdueBooks->isEmpty())
                    <p class="text-muted">Aucun livre en retard.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Livre</th><th>Membre</th><th>Retard (jours)</th></tr>
                            </thead>
                            <tbody>
                                @foreach($overdueBooks as $borrowing)
                                <tr class="table-danger">
                                    <td>{{ $borrowing->book->title }}</td>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ now()->diffInDays($borrowing->due_date) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5>📝 Réservations en attente</h5>
    </div>
    <div class="card-body">
        @if($pendingReservations->isEmpty())
            <p class="text-muted">Aucune réservation en attente.</p>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Livre</th><th>Membre</th><th>Position</th><th>Expire le</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pendingReservations as $reservation)
                        <tr>
                            <td>{{ $reservation->book->title }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->position }}</td>
                            <td>{{ $reservation->expires_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection