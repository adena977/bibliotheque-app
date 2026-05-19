@extends('layouts.app')

@section('title', 'Mes réservations')

@section('header', '📝 Mes réservations')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-clock"></i> Réservations en cours</h5>
    </div>
    <div class="card-body">
        @if($reservations->isEmpty())
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> Aucune réservation en cours.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Livre</th>
                            <th>Auteur</th>
                            <th>Position</th>
                            <th>Réservé le</th>
                            <th>Expire le</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                        <tr>
                            <td>
                                <strong>{{ $reservation->book->title }}</strong>
                            </td>
                            <td>{{ $reservation->book->author }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-chart-line"></i> #{{ $reservation->position }}
                                </span>
                            </td>
                            <td>{{ $reservation->reserved_at->format('d/m/Y') }}</td>
                            <td>
                                @if($reservation->expires_at->isToday())
                                    <span class="text-danger fw-bold">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $reservation->expires_at->format('d/m/Y') }}
                                    </span>
                                @else
                                    {{ $reservation->expires_at->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $reservation->id }}">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                                
                                <!-- Modal de confirmation -->
                                <div class="modal fade" id="cancelModal{{ $reservation->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Annuler la réservation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraiment annuler la réservation du livre <strong>"{{ $reservation->book->title }}"</strong> ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Oui, annuler</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="fas fa-history"></i> Historique des réservations</h5>
    </div>
    <div class="card-body">
        @if($history->isEmpty())
            <div class="alert alert-secondary mb-0">
                <i class="fas fa-info-circle"></i> Aucun historique de réservation.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Livre</th>
                            <th>Auteur</th>
                            <th>Date réservation</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $reservation)
                        <tr>
                            <td>{{ $reservation->book->title }}</td>
                            <td>{{ $reservation->book->author }}</td>
                            <td>{{ $reservation->reserved_at->format('d/m/Y') }}</td>
                            <td>
                                @switch($reservation->status)
                                    @case('converted')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Transformée en emprunt
                                        </span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-clock"></i> Expirée
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-ban"></i> Annulée
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-info">{{ $reservation->status }}</span>
                                @endswitch
                             </td>
                         </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>

@if($reservations->isEmpty() && $history->isEmpty())
<div class="alert alert-info text-center">
    <i class="fas fa-book fa-3x mb-2"></i>
    <p>Vous n'avez aucune réservation. Parcourez le <a href="{{ route('books.index') }}">catalogue</a> pour réserver un livre.</p>
</div>
@endif
@endsection