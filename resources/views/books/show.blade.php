@extends('layouts.app')

@section('title', $book->title)

@section('header', $book->title)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-book fa-5x text-primary mb-3"></i>
                <h4>{{ $book->title }}</h4>
                <p class="text-muted">par {{ $book->author }}</p>
                <hr>
                <div class="mb-2">
                    <strong>ISBN:</strong> {{ $book->isbn }}
                </div>
                <div class="mb-2">
                    <strong>Éditeur:</strong> {{ $book->publisher ?? 'Non spécifié' }}
                </div>
                <div class="mb-2">
                    <strong>Année:</strong> {{ $book->publication_year ?? 'Non spécifiée' }}
                </div>
                <div class="mb-2">
                    <strong>Pages:</strong> {{ $book->pages ?? 'Non spécifié' }}
                </div>
                <div class="mb-3">
                    <strong>Emplacement:</strong> {{ $book->location ?? 'Non spécifié' }}
                </div>
                <div class="alert {{ $book->is_available ? 'alert-success' : 'alert-danger' }}">
                    <strong>{{ $book->available_copies }}/{{ $book->total_copies }}</strong> exemplaires disponibles
                </div>
                
                @if(Auth::user()->isMember())
                    @php
                        $hasActiveBorrowing = \App\Models\Borrowing::where('user_id', Auth::id())
                            ->where('book_id', $book->id)
                            ->where('status', 'ongoing')
                            ->exists();
                        $hasActiveReservation = \App\Models\Reservation::where('user_id', Auth::id())
                            ->where('book_id', $book->id)
                            ->where('status', 'pending')
                            ->exists();
                    @endphp

                    @if($book->is_available)
                        @if(!$hasActiveBorrowing)
                            @if(Auth::user()->canBorrow())
                                <form action="{{ route('borrowings.member.store', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Confirmer l\'emprunt de ce livre ?')">
                                        <i class="fas fa-hand-holding"></i> Emprunter
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Vous ne pouvez pas emprunter.<br>
                                    @if(Auth::user()->total_fine >= 5000)
                                        Vos amendes dépassent 5000 DJF.
                                    @else
                                        Vous avez déjà {{ Auth::user()->getActiveBorrowings()->count() }} emprunt(s) sur 5 maximum.
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Vous avez déjà emprunté ce livre.
                            </div>
                        @endif
                    @else
                        @if(!$hasActiveBorrowing && !$hasActiveReservation)
                            @if(Auth::user()->canReserve())
                                <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#reserveModal">
                                    <i class="fas fa-clock"></i> Réserver
                                </button>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Vous ne pouvez pas réserver.<br>
                                    Vous avez déjà {{ Auth::user()->reservations()->where('status', 'pending')->count() }} réservation(s) sur 3 maximum.
                                </div>
                            @endif
                        @elseif($hasActiveReservation)
                            <div class="alert alert-info">
                                <i class="fas fa-clock"></i> Vous avez déjà réservé ce livre.
                            </div>
                        @elseif($hasActiveBorrowing)
                            <div class="alert alert-secondary">
                                <i class="fas fa-book"></i> Vous avez déjà emprunté ce livre.
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Description</div>
            <div class="card-body">
                <p>{{ $book->description ?? 'Aucune description disponible.' }}</p>
            </div>
        </div>
        
        @if(isset($reservations) && $reservations->isNotEmpty())
        <div class="card">
            <div class="card-header">Liste d'attente des réservations</div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($reservations as $reservation)
                    <li class="list-group-item">
                        Position {{ $reservation->position }}: {{ $reservation->user->name }}
                        <small class="text-muted">(Expire le {{ $reservation->expires_at->format('d/m/Y') }})</small>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Réservation -->
<div class="modal fade" id="reserveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Réserver ce livre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('reservations.store', $book) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Le livre "<strong>{{ $book->title }}</strong>" est actuellement indisponible.</p>
                    <p>Souhaitez-vous le réserver ? Vous serez notifié quand il sera disponible.</p>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle"></i> 
                        La réservation expire après 3 jours. Vous avez droit à 3 réservations maximum.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection