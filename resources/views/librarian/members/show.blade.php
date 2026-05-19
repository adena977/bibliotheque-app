@extends('layouts.app')

@section('title', 'Détail du membre')

@section('header', '👤 Détail du membre : ' . $member->name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5>Informations personnelles</h5>
            </div>
            <div class="card-body">
                <p><strong>Nom :</strong> {{ $member->name }}</p>
                <p><strong>Email :</strong> {{ $member->email }}</p>
                <p><strong>Téléphone :</strong> {{ $member->phone ?? 'Non renseigné' }}</p>
                <p><strong>Adresse :</strong> {{ $member->address ?? 'Non renseignée' }}</p>
                <p><strong>Date d'inscription :</strong> {{ $member->membership_date->format('d/m/Y') }}</p>
                <p>
                    <strong>Statut :</strong>
                    @if($member->is_active)
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </p>
                <p>
                    <strong>Total amendes :</strong>
                    @if($totalFines > 0)
                        <span class="text-danger fw-bold">{{ number_format($totalFines, 0, ',', ' ') }} DJF</span>
                    @else
                        <span class="text-success">0 DJF</span>
                    @endif
                </p>
            </div>
            <div class="card-footer">
                <a href="{{ route('librarian.members.edit', $member) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <button type="button" class="btn btn-sm {{ $member->is_active ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i class="fas {{ $member->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                    {{ $member->is_active ? 'Désactiver' : 'Activer' }}
                </button>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-warning">
                <h5>📖 Emprunts en cours</h5>
            </div>
            <div class="card-body">
                @if($activeBorrowings->isEmpty())
                    <p class="text-muted">Aucun emprunt en cours.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Livre</th><th>Date emprunt</th><th>Date retour prévue</th><th>Statut</th></tr>
                            </thead>
                            <tbody>
                                @foreach($activeBorrowings as $borrowing)
                                <tr class="{{ Carbon\Carbon::now()->gt($borrowing->due_date) ? 'table-danger' : '' }}">
                                    <td>{{ $borrowing->book->title }}<br><small>{{ $borrowing->book->author }}</small></td>
                                    <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                                    <td>{{ $borrowing->due_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if(Carbon\Carbon::now()->gt($borrowing->due_date))
                                            <span class="badge bg-danger">En retard</span>
                                        @else
                                            <span class="badge bg-success">En cours</span>
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
        
        <div class="card mb-3">
            <div class="card-header bg-danger text-white">
                <h5>💰 Amendes impayées</h5>
            </div>
            <div class="card-body">
                @if($pendingFines->isEmpty())
                    <p class="text-muted">Aucune amende impayée.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Livre</th><th>Montant</th><th>Date d'échéance</th><th>Statut</th></tr>
                            </thead>
                            <tbody>
                                @foreach($pendingFines as $fine)
                                <tr>
                                    <td>{{ $fine->borrowing->book->title ?? 'N/A' }}</td>
                                    <td>{{ number_format($fine->amount - $fine->paid_amount, 0, ',', ' ') }} DJF</td>
                                    <td>{{ $fine->due_date->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-warning">{{ $fine->status }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>📜 Historique des emprunts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Livre</th><th>Date emprunt</th><th>Date retour</th><th>Amende</th></tr>
                        </thead>
                        <tbody>
                            @forelse($borrowingHistory as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                                <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('d/m/Y') : '-' }}</td>
                                <td>{{ number_format($borrowing->fine, 0, ',', ' ') }} DJF</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Aucun historique</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $borrowingHistory->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Statut -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $member->is_active ? 'Désactiver' : 'Activer' }} le membre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment {{ $member->is_active ? 'désactiver' : 'activer' }} le compte de <strong>{{ $member->name }}</strong> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('librarian.members.toggle-status', $member) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $member->is_active ? 'btn-danger' : 'btn-success' }}">
                        {{ $member->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('librarian.members.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>
@endsection