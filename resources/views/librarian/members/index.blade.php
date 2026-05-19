@extends('layouts.app')

@section('title', 'Gestion des membres')

@section('header', '👥 Gestion des membres')

@section('content')
<div class="mb-3">
    <a href="{{ route('librarian.members.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nouveau membre
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, email ou téléphone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('librarian.members.index') }}" class="btn btn-secondary w-100">Réinitialiser</a>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                        <th>Amendes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>
                            <strong>{{ $member->name }}</strong>
                        </td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->phone ?? '-' }}</td>
                        <td>{{ $member->membership_date->format('d/m/Y') }}</td>
                        <td>
                            @if($member->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td>
                            @if($member->total_fine > 0)
                                <span class="text-danger fw-bold">{{ number_format($member->total_fine, 0, ',', ' ') }} DJF</span>
                            @else
                                <span class="text-success">0 DJF</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('librarian.members.show', $member) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('librarian.members.edit', $member) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm {{ $member->is_active ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#statusModal{{ $member->id }}">
                                <i class="fas {{ $member->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                            
                            <!-- Modal Statut -->
                            <div class="modal fade" id="statusModal{{ $member->id }}" tabindex="-1">
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
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Aucun membre trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection