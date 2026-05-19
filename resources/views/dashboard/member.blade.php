@extends('layouts.app')

@section('title', 'Mon tableau de bord')

@section('header', 'Bonjour, ' . Auth::user()->name)

@section('content')
<div class="dashboard-container">
    <!-- Cartes statistiques -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="fas fa-book-reader"></i></div>
            <div class="stat-info">
                <h3>{{ $activeBorrowings->count() }}</h3>
                <p>Emprunts actifs</p>
                <small>Limité à 5</small>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info">
                <h3>{{ $overdueBorrowings->count() }}</h3>
                <p>En retard</p>
                <small>Retour urgent</small>
            </div>
        </div>
        <div class="stat-card danger">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-info">
                <h3>{{ number_format($totalFine, 0, ',', ' ') }} DJF</h3>
                <p>Total amendes</p>
                <small>À régler</small>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <h3>{{ $activeReservations->count() }}</h3>
                <p>Réservations</p>
                <small>En attente</small>
            </div>
        </div>
    </div>

    <!-- Emprunts actifs -->
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="fas fa-hand-holding-heart"></i> Mes emprunts en cours
        </div>
        <div class="card-body-modern">
            @if($activeBorrowings->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-smile-wink"></i>
                    <p>Vous n'avez aucun emprunt actif. Profitez du catalogue !</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table-elegant">
                        <thead>
                            <tr><th>Livre</th><th>Auteur</th><th>Emprunté le</th><th>Retour prévu</th><th>Statut</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach($activeBorrowings as $borrowing)
                            <tr class="{{ $borrowing->isOverdue() ? 'overdue-row' : '' }}">
                                <td><strong>{{ $borrowing->book->title }}</strong></td>
                                <td>{{ $borrowing->book->author }}</td>
                                <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                                <td class="{{ $borrowing->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                    {{ $borrowing->due_date->format('d/m/Y') }}
                                    @if($borrowing->isOverdue()) <span class="badge-overdue">Retard</span> @endif
                                </td>
                                <td><span class="status-badge ongoing">En cours</span></td>
                                <td>
                                    @if($borrowing->canExtend())
                                        <a href="{{ route('borrowings.extend', $borrowing->id) }}" class="btn-extend" onclick="return confirm('Prolonger de 7 jours ?')">Prolonger</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Livres recommandés -->
    <div class="card-modern mt-4">
        <div class="card-header-modern">
            <i class="fas fa-star"></i> Recommandations du moment
        </div>
        <div class="card-body-modern">
            <div class="books-grid">
                @foreach($recommendedBooks as $book)
                <div class="book-card">
                    <div class="book-cover"><i class="fas fa-book-open fa-3x"></i></div>
                    <h6>{{ Str::limit($book->title, 35) }}</h6>
                    <small>{{ $book->author }}</small>
                    <a href="{{ route('books.show', $book) }}" class="btn-book">Détails</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: white;
        border-radius: 28px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #eef2f6;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -12px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        background: #f0f4fe;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .stat-card.primary .stat-icon { background: #eef2ff; color: #3b82f6; }
    .stat-card.warning .stat-icon { background: #fffbeb; color: #f59e0b; }
    .stat-card.danger .stat-icon { background: #fee2e2; color: #ef4444; }
    .stat-card.success .stat-icon { background: #dcfce7; color: #22c55e; }
    .stat-info h3 { font-size: 1.9rem; font-weight: 700; margin: 0; }
    .stat-info p { margin: 0; font-weight: 500; color: #1e293b; }
    .stat-info small { font-size: 0.7rem; color: #6b7280; }

    .card-modern {
        background: white;
        border-radius: 28px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #eef2f6;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .card-header-modern {
        padding: 18px 24px;
        font-weight: 600;
        font-size: 1.1rem;
        border-bottom: 1px solid #eef2f6;
        background: #fafcff;
    }
    .card-body-modern {
        padding: 24px;
    }
    .table-elegant {
        width: 100%;
        border-collapse: collapse;
    }
    .table-elegant th, .table-elegant td {
        padding: 14px 12px;
        border-bottom: 1px solid #eef2f6;
        vertical-align: middle;
    }
    .table-elegant th {
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
    }
    .overdue-row {
        background-color: #fff8f0;
    }
    .badge-overdue {
        background: #fef3c7;
        color: #d97706;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 8px;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-badge.ongoing { background: #dcfce7; color: #15803d; }
    .btn-extend {
        background: #eef2ff;
        color: #3b82f6;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-extend:hover { background: #3b82f6; color: white; }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 20px;
    }
    .book-card {
        text-align: center;
        background: #fafcff;
        padding: 16px;
        border-radius: 24px;
        transition: 0.2s;
    }
    .book-card:hover { background: #f1f5f9; transform: translateY(-3px); }
    .book-cover { margin-bottom: 12px; color: #3b82f6; }
    .btn-book {
        display: inline-block;
        margin-top: 12px;
        font-size: 0.75rem;
        background: #eef2ff;
        padding: 6px 12px;
        border-radius: 40px;
        text-decoration: none;
        color: #3b82f6;
    }
</style>
@endsection