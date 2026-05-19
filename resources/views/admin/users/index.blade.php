@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('header', '👑 Gestion des utilisateurs')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Ajouter un utilisateur
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateurs</option>
                    <option value="librarian" {{ request('role') == 'librarian' ? 'selected' : '' }}>Bibliothécaires</option>
                    <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Membres</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">Réinitialiser</a>
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
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date inscription</th>
                        <th>Amendes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">👑 Admin</span>
                            @elseif($user->role == 'librarian')
                                <span class="badge bg-primary">📚 Bibliothécaire</span>
                            @else
                                <span class="badge bg-secondary">👤 Membre</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td>{{ $user->membership_date->format('d/m/Y') }}</td>
                        <td>
                            @if($user->total_fine > 0)
                                <span class="text-danger">{{ number_format($user->total_fine, 0, ',', ' ') }} DJF</span>
                            @else
                                <span class="text-success">0 DJF</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" data-bs-toggle="modal" data-bs-target="#statusModal{{ $user->id }}">
                                <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                            </button>
                            @if($user->id != Auth::id())
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                            
                            <!-- Modal Statut -->
                            <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $user->is_active ? 'Désactiver' : 'Activer' }} l'utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment {{ $user->is_active ? 'désactiver' : 'activer' }} le compte de <strong>{{ $user->name }}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Suppression -->
                            @if($user->id != Auth::id())
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Supprimer l'utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer le compte de <strong>{{ $user->name }}</strong> ?<br>
                                            <small class="text-danger">Cette action est irréversible !</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="9" class="text-center">Aucun utilisateur trouvé. <a href="{{ route('admin.users.create') }}">Ajouter un utilisateur</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection