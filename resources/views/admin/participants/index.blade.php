<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Participants</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .card {
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color:rgb(249, 213, 243);
            border-bottom: 1px solid #eee;
        }
        .modal-lg {
            max-width: 800px;
        }
        .text-muted { color:rgb(0, 0, 0); }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des Participants</h3>
                    </div>
                    <div class="card-body">
                        @if($participants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
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
                                                <button type="button" class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $participant->idParticipant }}"
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
        </div>
    </div>

    <!-- Modales de détail -->
    @foreach($participants as $participant)
        @if($participant->user)
        <div class="modal fade" id="detailModal{{ $participant->idParticipant }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails du Participant #{{ $participant->idParticipant }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    
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
</body>
</html>