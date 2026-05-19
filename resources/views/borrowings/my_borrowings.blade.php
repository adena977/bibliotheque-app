@extends('layouts.app')

@section('title', 'Mes emprunts')

@section('header', '📖 Mes emprunts')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5>Emprunts en cours</h5>
    </div>
    <div class="card-body">
        @if($activeBorrowings->isEmpty())
            <p class="text-muted">Aucun emprunt en cours.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Livre</th>
                            <th>Auteur</th>
                            <th>Date emprunt</th>
                            <th>Date retour prévue</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->book->author }}</td>
                                <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                                <td class="{{ $borrowing->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                    {{ $borrowing->due_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($borrowing->isOverdue())
                                        <span class="badge bg-danger">En retard</span>
                                    @else
                                        <span class="badge bg-success">En cours</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->canExtend())
                                        <form action="{{ route('borrowings.extend', $borrowing->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Prolonger ce livre de 7 jours ?')">
                                                Prolonger
                                            </button>
                                        </form>
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

<div class="card">
    <div class="card-header">
        <h5>Historique des emprunts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Date emprunt</th>
                        <th>Date retour réelle</th>
                        <th>Amende</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $borrowing)
                        <tr>
                            <td>{{ $borrowing->book->title }}</td>
                            <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                            <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('d/m/Y') : '-' }}</td>
                            <td>{{ number_format($borrowing->fine, 0, ',', ' ') }} DJF</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun historique</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $history->links() }}
    </div>
</div>
@endsection