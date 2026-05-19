@extends('layouts.app')

@section('title', 'Paramètres')

@section('header', '⚙️ Paramètres de l\'application')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-building"></i> Informations générales</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Nom de la bibliothèque</label>
                        <input type="text" name="library_name" class="form-control" value="{{ $settings['library_name'] }}">
                    </div>
                    
                    <div class="mb-3">
                        <label>Téléphone</label>
                        <input type="text" name="library_phone" class="form-control" value="{{ $settings['library_phone'] }}">
                    </div>
                    
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="library_email" class="form-control" value="{{ $settings['library_email'] }}">
                    </div>
                    
                    <div class="mb-3">
                        <label>Adresse</label>
                        <textarea name="library_address" class="form-control" rows="2">{{ $settings['library_address'] }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label>Logo</label>
                        <input type="file" name="library_logo" class="form-control" accept="image/*">
                        @if($settings['library_logo'])
                            <div class="mt-2">
                                <img src="{{ asset('uploads/settings/' . $settings['library_logo']) }}" alt="Logo" height="50">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Horaires d'ouverture -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-clock"></i> Horaires d'ouverture</h5>
                </div>
                <div class="card-body">
                    @php
                        $hours = json_decode($settings['opening_hours'], true);
                        $days = ['monday' => 'Lundi', 'tuesday' => 'Mardi', 'wednesday' => 'Mercredi', 'thursday' => 'Jeudi', 'friday' => 'Vendredi', 'saturday' => 'Samedi', 'sunday' => 'Dimanche'];
                    @endphp
                    
                    @foreach($days as $key => $day)
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>{{ $day }}</strong>
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="opening_hours[{{ $key }}][open]" class="form-control form-control-sm" value="{{ $hours[$key]['open'] ?? '' }}" placeholder="Ouvre">
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="opening_hours[{{ $key }}][close]" class="form-control form-control-sm" value="{{ $hours[$key]['close'] ?? '' }}" placeholder="Ferme">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Paramètres d'emprunt -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-hand-holding"></i> Paramètres d'emprunt</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Amende par jour (DJF)</label>
                        <input type="number" name="fine_per_day" class="form-control" value="{{ $settings['fine_per_day'] }}" min="10" max="500">
                        <small class="text-muted">Montant de l'amende par jour de retard</small>
                    </div>
                    
                    <div class="mb-3">
                        <label>Durée max d'emprunt (jours)</label>
                        <input type="number" name="max_borrow_days" class="form-control" value="{{ $settings['max_borrow_days'] }}" min="1" max="60">
                    </div>
                    
                    <div class="mb-3">
                        <label>Nombre max de livres par emprunt</label>
                        <input type="number" name="max_borrow_books" class="form-control" value="{{ $settings['max_borrow_books'] }}" min="1" max="20">
                    </div>
                    
                    <div class="mb-3">
                        <label>Durée de prolongation (jours)</label>
                        <input type="number" name="max_extend_days" class="form-control" value="{{ $settings['max_extend_days'] }}" min="1" max="30">
                    </div>
                    
                    <div class="mb-3">
                        <label>Nombre max de prolongations</label>
                        <input type="number" name="max_extend_count" class="form-control" value="{{ $settings['max_extend_count'] }}" min="0" max="5">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paramètres de réservation -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5><i class="fas fa-clock"></i> Paramètres de réservation</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Nombre max de réservations actives</label>
                        <input type="number" name="max_reservations" class="form-control" value="{{ $settings['max_reservations'] }}" min="1" max="10">
                    </div>
                    
                    <div class="mb-3">
                        <label>Expiration réservation (jours)</label>
                        <input type="number" name="reservation_expiry_days" class="form-control" value="{{ $settings['reservation_expiry_days'] }}" min="1" max="7">
                        <small class="text-muted">Une réservation expire après ce délai si non transformée en emprunt</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paramètres d'amende -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5><i class="fas fa-money-bill"></i> Paramètres d'amende</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Seuil de blocage (DJF)</label>
                        <input type="number" name="fine_threshold" class="form-control" value="{{ $settings['fine_threshold'] }}" min="1000" max="20000">
                        <small class="text-muted">Les membres avec des amendes > ce seuil ne peuvent pas emprunter</small>
                    </div>
                    
                    <div class="mb-3">
                        <label>Jours de grâce</label>
                        <input type="number" name="grace_period_days" class="form-control" value="{{ $settings['grace_period_days'] }}" min="0" max="7">
                        <small class="text-muted">Nombre de jours avant application de l'amende</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Paramètres de notification -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5><i class="fas fa-bell"></i> Paramètres de notification</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="enable_notifications" class="form-check-input" value="1" {{ $settings['enable_notifications'] ? 'checked' : '' }}>
                        <label class="form-check-label">Activer les notifications</label>
                    </div>
                    
                    <div class="mb-3">
                        <label>Notifier avant (jours)</label>
                        <input type="number" name="notify_days_before" class="form-control" value="{{ $settings['notify_days_before'] }}" min="0" max="7">
                        <small class="text-muted">Envoyer un rappel X jours avant la date de retour</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5><i class="fas fa-cog"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les paramètres
                        </button>
                        <a href="{{ route('admin.settings.clear-cache') }}" class="btn btn-warning" onclick="return confirm('Vider le cache ?')">
                            <i class="fas fa-trash-alt"></i> Vider le cache
                        </a>
                        <a href="{{ route('admin.settings.reset') }}" class="btn btn-danger" onclick="return confirm('Réinitialiser tous les paramètres ? Cette action est irréversible.')">
                            <i class="fas fa-undo"></i> Réinitialiser les paramètres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection