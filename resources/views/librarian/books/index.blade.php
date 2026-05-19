@extends('layouts.app')

@section('title', 'Gestion des livres')

@section('header', '📚 Gestion des livres')

@section('content')
<div class="mb-3">
    <a href="{{ route('librarian.books.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un livre
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="booksTable">
                <thead>
                    <tr><th>Titre</th><th>Auteur</th><th>ISBN</th><th>Exemplaires</th><th>Dispo</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->available_copies }}/{{ $book->total_copies }}</td>
                        <td>
                            <span class="badge {{ $book->is_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $book->is_available ? 'Disponible' : 'Indisponible' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('librarian.books.edit', $book) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('librarian.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce livre ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $books->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#booksTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json' }
        });
    });
</script>
@endpush