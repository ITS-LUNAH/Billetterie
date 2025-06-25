@extends('layouts.admin') {{-- Indique d'étendre le layout principal admin --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestion des Participants</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Participants</h6>
        </div>
        <div class="card-body">
            @if($participants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="participantsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom & Prénom</th>
                                <th>Email</th>
                                <th>Compte Bancaire</th>
                                <th>IBAN</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $participant)
                            <tr>
                                <td>{{ $participant->idParticipant }}</td>
                                <td>
                                    @if($participant->user)
                                        {{ $participant->user->lastName }} {{ $participant->user->firstName }}
                                        @if(empty($participant->user->lastName) && empty($participant->user->firstName))
                                            <span class="text-danger">(nom/prénom manquants)</span>
                                        @endif
                                    @else
                                        <span class="text-danger">(utilisateur non lié)</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $participant->user->email ?? 'Non défini' }}
                                </td>
                                <td>
                                    {{ $participant->compteBancaire ?? 'Non renseigné' }}
                                </td>
                                <td class="iban-format">
                                    {{ $participant->IBAN ?? 'Non renseigné' }}
                                </td>
                                <td>
                                    {{ $participant->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    {{-- Modifié pour Bootstrap 4: data-toggle="modal", data-target="#id" --}}
                                    <button type="button" class="btn btn-sm btn-info"
                                        data-toggle="modal"
                                        data-target="#detailModal{{ $participant->idParticipant }}"
                                        @if(!$participant->user) disabled @endif>
                                        <i class="fas fa-eye"></i> Voir
                                    </button>

                                    <a href="{{ route('admin.participants.edit', $participant->idParticipant) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>

                                    <form action="{{ route('admin.participants.destroy', $participant->idParticipant) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce participant et son compte utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucun participant inscrit pour le moment.
                </div>
            @endif
        </div>
    </div>
</div>

@foreach($participants as $participant)
    @if($participant->user)
    <div class="modal fade" id="detailModal{{ $participant->idParticipant }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails du Participant #{{ $participant->idParticipant }}</h5>
                    {{-- Modifié pour Bootstrap 4: class="close" data-dismiss="modal" --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations personnelles</h5>
                            <p><strong>Nom complet :</strong>
                                {{ $participant->user->lastName ?? 'Non spécifié' }} {{ $participant->user->firstName ?? '' }}</p>
                            <p><strong>Email :</strong> {{ $participant->user->email }}</p>
                            <p><strong>Téléphone :</strong> {{ $participant->user->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Informations bancaires</h5>
                            <p><strong>Titulaire :</strong> {{ $participant->compteBancaire ?? 'Non renseigné' }}</p>
                            <p><strong>IBAN :</strong> <span class="iban-format">{{ $participant->IBAN ?? 'Non renseigné' }}</span></p>
                            <p><strong>RIB :</strong> {{ $participant->RIB ?? 'Non renseigné' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Statistiques</h5>
                            <p><strong>Réservations :</strong> {{ $participant->reservations_count ?? 0 }}</p>
                            <p><strong>Billets :</strong> {{ $participant->billets_count ?? 0 }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Dates</h5>
                            <p><strong>Inscription :</strong> {{ $participant->created_at->format('d/m/Y à H:i') }}</p>
                            <p><strong>Dernière activité :</strong> {{ $participant->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- Modifié pour Bootstrap 4: data-dismiss="modal" --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection {{-- Fin de la section 'content' --}}

@push('scripts')
{{-- Le JavaScript de jQuery et Bootstrap est déjà chargé dans admin.blade.php.
     Ne chargez ici que le script spécifique à cette page. --}}
<script>
// Formatage des IBAN
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.iban-format').forEach(el => {
        if (el.textContent.trim().length > 4 && el.textContent.trim() !== 'Non renseigné') {
            el.textContent = el.textContent.trim().replace(/(.{4})/g, '$1 ').trim();
        }
    });
});
</script>
@endpush

{{-- PAS DE BALISES <head> OU <body> ICI --}}