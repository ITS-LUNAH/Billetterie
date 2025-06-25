@extends('layouts.admin')

@section('title', 'Dashboard Admin - EVUP')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endpush

@section('content')
<h1>Dashboard</h1>

<div class="date">
    <input type="date" value="{{ date('Y-m-d') }}">
</div>

<div class="insights">
    @foreach($stats as $stat)
        <div class="{{ strtolower($stat['title']) }}">
            <span class="material-icons-sharp">{{ $stat['icon'] ?? 'analytics' }}</span>
            <div class="middle">
                <div class="left">
                    <h3>{{ $stat['title'] }}</h3>
                    <h1>{{ $stat['value'] }}</h1>
                </div>
                <div class="progress">
                    <svg>
                        <circle cx="40" cy="40" r="36"></circle>
                        <circle class="progress-bar" cx="40" cy="40" r="36" style="stroke-dashoffset: calc(226 - (226 * {{ $stat['pourcentage'] ?? 0 }}) / 100);"></circle>
                    </svg>
                <div class="number"><p>{{ $stat['pourcentage'] ?? 0 }}%</p></div>
            </div>
            
            <small class="text-muted">Dernières 24 heures</small>
        </div>
    @endforeach
</div>
    
    <!--<div class="expenses">
        <span class="material-icons-sharp">bar_chart</span>
        <div class="middle">
            <div class="left">
                <h3>Revenu Total</h3>
                <h1>14,160€</h1>
            </div>
            <div class="progress">
                <svg>
                    <circle cx='38' cy='38' r='36'></circle>
                </svg>
                <div class="number">
                    <p>62%</p>
                </div>
            </div>
        </div>
        <small class="text-muted">Dernières 24 heures</small>
    </div>
    
    <div class="income">
        <span class="material-icons-sharp">stacked_line_chart</span>
        <div class="middle">
            <div class="left">
                <h3>Réservations</h3>
                <h1>10,864</h1>
            </div>
            <div class="progress">
                <svg>
                    <circle cx='38' cy='38' r='36'></circle>
                </svg>
                <div class="number">
                    <p>44%</p>
                </div>
            </div>
        </div>
        <small class="text-muted">Dernières 24 heures</small>
    </div>-->
</div>

<div class="recent-orders">
    <h2>Réservations Récentes</h2>

    @if($recentReservations->isEmpty())
        <p>Aucune réservation récente.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Nom de l'Événement</th>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Statut</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentReservations as $reservation)
                <tr>
                    <td>{{ $reservation->event->titre ?? 'N/A' }}</td>
                    <td>{{ $reservation->user->name ?? 'N/A' }}</td>
                    <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                    <td class="{{ $reservation->status === 'confirmé' ? 'success' : 'warning' }}">
                        {{ ucfirst($reservation->status) }}
                    </td>
                    <td><a href="#">Détails</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <a href="{{ route('admin.evenements.index') }}">Voir tous les événements</a>
</div>

@endsection