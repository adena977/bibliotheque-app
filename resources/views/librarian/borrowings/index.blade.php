@extends('layouts.app')

@section('title', 'Gestion des emprunts')

@section('header', '📖 Gestion des emprunts')

@section('content')
<div class="mb-3">
    <a href="{{ route('librarian.borrowings.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel emprunt
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>En cours</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Retournés</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>En retard</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('librarian.borrowings.index') }}" class="btn btn-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Membre</th>
                        <th>Livre</th>
                        <th>Date emprunt</th>
                        <th>Date retour prévue</th>
                        <th>Statut</th>
                        <th>Amende</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->id }}</td>
                        <td>
                            {{ $borrowing->user->name }}<br>
                            <small class="text-muted">{{ $borrowing->user->email }}</small>
                        </td>
                        <td>{{ $borrowing->book->title }}<br><small>{{ $borrowing->book->author }}</small></td>
                        <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                        <td class="{{ Carbon\Carbon::now()->gt($borrowing->due_date) && $borrowing->status == 'ongoing' ? 'text-danger fw-bold' : '' }}">
                            {{ $borrowing->due_date->format('d/m/Y') }}
                            @if(Carbon\Carbon::now()->gt($borrowing->due_date) && $borrowing->status == 'ongoing')
                                <span class="badge bg-danger">Retard</span>
                            @endif
                        </td>
                        <td>
                            @if($borrowing->status == 'ongoing')
                                <span class="badge bg-success">En cours</span>
                            @elseif($borrowing->status == 'returned')
                                <span class="badge bg-secondary">Retourné</span>
                            @elseif($borrowing->status == 'overdue')
                                <span class="badge bg-danger">En retard</span>
                            @else
                                <span class="badge bg-info">{{ $borrowing->status }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($borrowing->fine, 0, ',', ' ') }} DJF</td>
                        <td>
                            @if($borrowing->status == 'ongoing')
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnModal{{ $borrowing->id }}">
                                    <i class="fas fa-undo"></i> Retour
                                </button>
                                
                                <!-- Modal Retour -->
                                <div class="modal fade" id="returnModal{{ $borrowing->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer le retour</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Confirmer le retour du livre <strong>"{{ $borrowing->book->title }}"</strong> par <strong>{{ $borrowing->user->name }}</strong> ?</p>
                                                @php
                                                    $daysLate = Carbon\Carbon::now()->diffInDays($borrowing->due_date, false);
                                                @endphp
                                                @if($daysLate > 0)
                                                    <div class="alert alert-warning">
                                                        <strong>⚠️ Attention :</strong> Retard de {{ $daysLate }} jours.<br>
                                                        Amende estimée : <strong>{{ number_format($daysLate * 50, 0, ',', ' ') }} DJF</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('librarian.borrowings.return', ['book' => $borrowing->book_id, 'user' => $borrowing->user_id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Confirmer le retour</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('librarian.borrowings.show', $borrowing) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Aucun emprunt trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $borrowings->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection