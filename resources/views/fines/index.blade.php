@extends('layouts.app')

@section('title', 'Mes amendes')

@section('header', '💰 Mes amendes')

@section('content')
@if($totalDue > 0)
<div class="alert alert-danger">
    <strong>Total à payer : {{ number_format($totalDue, 0, ',', ' ') }} DJF</strong>
</div>
@endif

<div class="card mb-4">
    <div class="card-header">
        <h5>Amendes impayées</h5>
    </div>
    <div class="card-body">
        @if($fines->isEmpty())
            <p class="text-success">Aucune amende impayée. 👍</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Livre</th><th>Montant</th><th>Dû par</th><th>Reste à payer</th></tr>
                    </thead>
                    <tbody>
                        @foreach($fines as $fine)
                        <tr>
                            <td>{{ $fine->borrowing->book->title ?? 'N/A' }}</td>
                            <td>{{ number_format($fine->amount, 0, ',', ' ') }} DJF</td>
                            <td>{{ $fine->due_date->format('d/m/Y') }}</td>
                            <td>{{ number_format($fine->amount - $fine->paid_amount, 0, ',', ' ') }} DJF</td>
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
        <h5>Historique des amendes payées</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr><th>Livre</th><th>Montant</th><th>Payé le</th></tr>
                </thead>
                <tbody>
                    @forelse($history as $fine)
                    <tr>
                        <td>{{ $fine->borrowing->book->title ?? 'N/A' }}</td>
                        <td>{{ number_format($fine->amount, 0, ',', ' ') }} DJF</td>
                        <td>{{ $fine->paid_at ? $fine->paid_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Aucun historique</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $history->links() }}
    </div>
</div>
@endsection