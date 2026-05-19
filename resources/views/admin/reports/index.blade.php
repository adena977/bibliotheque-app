@extends('layouts.app')

@section('title', 'Rapports et statistiques')

@section('header', '📊 Rapports et statistiques')

@section('content')
<!-- Cartes de statistiques -->
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs</h5>
                <h2>{{ $totalUsers }}</h2>
                <small>{{ $activeUsers }} actifs</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Livres</h5>
                <h2>{{ $totalBooks }}</h2>
                <small>{{ $availableBooks }} disponibles</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Emprunts</h5>
                <h2>{{ $totalBorrowings }}</h2>
                <small>{{ $activeBorrowings }} en cours</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Amendes</h5>
                <h2>{{ number_format($totalFines, 0, ',', ' ') }} DJF</h2>
                <small>{{ number_format($pendingFines, 0, ',', ' ') }} DJF impayés</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Évolution mensuelle des emprunts -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>📈 Évolution mensuelle des emprunts</h5>
            </div>
            <div class="card-body">
                <canvas id="borrowingsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Taux de retour à l'heure -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>✅ Taux de retour à l'heure</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-1">{{ $onTimeRate }}%</h1>
                <div class="progress mt-3" style="height: 30px;">
                    <div class="progress-bar bg-success" style="width: {{ $onTimeRate }}%">
                        {{ $onTimeRate }}%
                    </div>
                </div>
                <p class="mt-3 text-muted">
                    {{ $completedBorrowings }} emprunts terminés<br>
                    {{ $overdueBorrowings }} en retard actuellement
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top 10 livres -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h5>🏆 Top 10 des livres les plus empruntés</h5>
            </div>
            <div class="card-body">
                @if($topBooks->isEmpty())
                    <p class="text-muted">Aucune donnée disponible.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>#</th><th>Livre</th><th>Auteur</th><th>Emprunts</th></tr>
                            </thead>
                            <tbody>
                                @foreach($topBooks as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->book->title ?? 'N/A' }}</td>
                                    <td>{{ $item->book->author ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary">{{ $item->total }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Top 10 membres -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>🌟 Top 10 des membres les plus actifs</h5>
            </div>
            <div class="card-body">
                @if($topMembers->isEmpty())
                    <p class="text-muted">Aucune donnée disponible.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>#</th><th>Membre</th><th>Email</th><th>Emprunts</th></tr>
                            </thead>
                            <tbody>
                                @foreach($topMembers as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                                    <td>{{ $item->user->email ?? 'N/A' }}</td>
                                    <td><span class="badge bg-success">{{ $item->total }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Statistiques par catégorie -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5>📚 Livres par catégorie</h5>
            </div>
            <div class="card-body">
                                <canvas id="categoryChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Revenus des amendes -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5>💰 Revenus des amendes par mois</h5>
            </div>
            <div class="card-body">
                <canvas id="fineRevenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Actions d'export -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5>📥 Export des rapports</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2">
                <a href="{{ route('admin.reports.export', ['type' => 'books', 'format' => 'csv']) }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-book"></i> Livres (CSV)
                </a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="{{ route('admin.reports.export', ['type' => 'borrowings', 'format' => 'csv']) }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-hand-holding"></i> Emprunts (CSV)
                </a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}" class="btn btn-outline-info w-100">
                    <i class="fas fa-users"></i> Utilisateurs (CSV)
                </a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="{{ route('admin.reports.export', ['type' => 'fines', 'format' => 'csv']) }}" class="btn btn-outline-warning w-100">
                    <i class="fas fa-money-bill"></i> Amendes (CSV)
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des emprunts mensuels
    const monthlyStats = @json($monthlyStats);
    const borrowingsCtx = document.getElementById('borrowingsChart').getContext('2d');
    new Chart(borrowingsCtx, {
        type: 'line',
        data: {
            labels: monthlyStats.map(s => s.month),
            datasets: [{
                label: 'Nombre d\'emprunts',
                data: monthlyStats.map(s => s.count),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
    
    // Graphique des catégories
    const categoryStats = @json($categoryStats);
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryStats.map(c => c.name),
            datasets: [{
                data: categoryStats.map(c => c.total_books),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#C9CBCF', '#7C4DFF', '#00BCD4', '#FF5722'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
    
    // Graphique des revenus d'amendes
    const fineRevenue = @json($fineRevenue);
    const fineCtx = document.getElementById('fineRevenueChart').getContext('2d');
    new Chart(fineCtx, {
        type: 'bar',
        data: {
            labels: fineRevenue.map(r => r.month),
            datasets: [{
                label: 'Amendes payées (DJF)',
                data: fineRevenue.map(r => r.amount),
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Montant (DJF)' } },
                x: { title: { display: true, text: 'Mois' } }
            }
        }
    });
</script>
@endpush
@endsection