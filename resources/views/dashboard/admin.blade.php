@extends('layouts.app')

@section('title', 'Tableau de bord - Admin')

@section('header', '📊 Tableau de bord administrateur')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs</h5>
                <h2>{{ $totalUsers }}</h2>
                <small>{{ $activeUsers }} actifs</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Livres</h5>
                <h2>{{ $totalBooks }}</h2>
                <small>{{ $availableBooks }} disponibles</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Emprunts actifs</h5>
                <h2>{{ $activeBorrowings }}</h2>
                <small>{{ $overdueBorrowings }} en retard</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Amendes totales</h5>
                <h2>{{ number_format($totalFines, 0, ',', ' ') }} DJF</h2>
                <small>Ce mois: {{ number_format($monthlyBorrowings, 0, ',', ' ') }} emprunts</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5>🏆 Top 5 des livres les plus empruntés</h5>
            </div>
            <div class="card-body">
                @if($topBooks->isEmpty())
                    <p class="text-muted">Aucune donnée</p>
                @else
                    <ul class="list-group">
                        @foreach($topBooks as $book)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $book->book->title }}
                            <span class="badge bg-primary rounded-pill">{{ $book->count }} emprunts</span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5>📋 Derniers emprunts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Livre</th><th>Membre</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @foreach($recentBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->user->name }}</td>
                                <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection