@extends('layouts.admin') {{-- Assurez-vous que votre layout admin est correctement inclus --}}

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestion des Événements</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Événements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="evenementsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Organisateur</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Statut Valide</th> {{-- Renommé pour être plus clair sur 'estValide' --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evenements as $evenement)
                        <tr>
                            <td>{{ $evenement->idevenement }}</td>
                            <td>{{ $evenement->titre }}</td>
                            <td>{{ $evenement->organisateur->nomComplet ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($evenement->dateEvenement)->format('d/m/Y H:i') }}</td>
                            <td>{{ $evenement->lieu }}</td>
                            <td>
                                @if ($evenement->estValide)
                                    <span class="badge badge-success">Validé</span>
                                    <form action="{{ route('admin.evenements.refuser', $evenement->idevenement) }}" method="POST" style="display:inline;" onsubmit="return confirm('Refuser cet événement ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger ml-1" title="Refuser">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge badge-warning">En attente / Refusé</span>
                                    <form action="{{ route('admin.evenements.valider', $evenement->idevenement) }}" method="POST" style="display:inline;" onsubmit="return confirm('Valider cet événement ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success ml-1" title="Valider">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <td>
                                {{-- Bouton pour afficher les détails (MODALE) - MAINTENANT AVEC ICÔNE --}}
                                <button type="button" class="btn btn-info btn-sm mb-1" data-toggle="modal" data-target="#evenementDetailModal"
                                    data-id="{{ $evenement->idevenement }}"
                                    data-titre="{{ $evenement->titre }}"
                                    data-description="{{ $evenement->description }}"
                                    data-organisateur="{{ $evenement->organisateur->nomComplet ?? 'N/A' }}"
                                    data-date="{{ \Carbon\Carbon::parse($evenement->dateEvenement)->format('d/m/Y à H:i') }}"
                                    data-lieu="{{ $evenement->lieu }}"
                                    data-capacite="{{ $evenement->capacite }}"
                                    data-estValide="{{ $evenement->estValide ? 'Validé' : 'Refusé' }}"
                                    data-created-at="{{ $evenement->created_at ? $evenement->created_at->format('d/m/Y à H:i') : 'N/A' }}"
                                    data-updated-at="{{ $evenement->updated_at ? $evenement->updated_at->format('d/m/Y à H:i') : 'N/A' }}"
                                    title="Voir Détails">
                                    <i class="fas fa-eye"></i> {{-- Icône "œil" pour voir les détails --}}
                                </button>

                                {{-- Bouton Modifier - MAINTENANT AVEC ICÔNE --}}
                                <a href="{{ route('admin.evenements.edit', $evenement->idevenement) }}" class="btn btn-warning btn-sm mb-1" title="Modifier">
                                    <i class="fas fa-edit"></i> {{-- Icône "crayon" pour modifier --}}
                                </a>

                                {{-- Bouton Supprimer - MAINTENANT AVEC ICÔNE --}}
                                <form action="{{ route('admin.evenements.destroy', $evenement->idevenement) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer">
                                        <i class="fas fa-trash"></i> {{-- Icône "poubelle" pour supprimer --}}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="evenementDetailModal" tabindex="-1" role="dialog" aria-labelledby="evenementDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evenementDetailModalLabel">Détails de l'Événement #<span id="modalEvenementId"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Informations générales</h4>
                        <p><strong>Titre :</strong> <span id="modalTitre"></span></p>
                        <p><strong>Organisateur :</strong> <span id="modalOrganisateur"></span></p>
                        <p><strong>Date & Heure :</strong> <span id="modalDate"></span></p>
                        <p><strong>Lieu :</strong> <span id="modalLieu"></span></p>
                        <p><strong>Capacité :</strong> <span id="modalCapacite"></span></p>
                        <p><strong>Statut Valide :</strong> <span id="modalEstValide"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Description</h4>
                        <p><span id="modalDescription"></span></p>
                        <h4>Dates Clés</h4>
                        <p><strong>Créé le :</strong> <span id="modalCreatedAt"></span></p>
                        <p><strong>Dernière mise à jour :</strong> <span id="modalUpdatedAt"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Assurez-vous que jQuery et Bootstrap JS sont chargés pour le bon fonctionnement des modales et des alertes --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Fonction pour afficher une alerte temporaire (inchangée)
    function showTemporaryAlert(message, type = 'success') {
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        $('.container-fluid').prepend(alertHtml);
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Script pour la modale de détails de l'événement (inchangé)
    $('#evenementDetailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var titre = button.data('titre');
        var description = button.data('description');
        var organisateur = button.data('organisateur');
        var date = button.data('date');
        var lieu = button.data('lieu');
        var capacite = button.data('capacite');
        var estValide = button.data('estvalide');
        var createdAt = button.data('created-at');
        var updatedAt = button.data('updated-at');

        var modal = $(this);
        modal.find('#modalEvenementId').text(id);
        modal.find('#modalTitre').text(titre);
        modal.find('#modalDescription').text(description);
        modal.find('#modalOrganisateur').text(organisateur);
        modal.find('#modalDate').text(date);
        modal.find('#modalLieu').text(lieu);
        modal.find('#modalCapacite').text(capacite);
        modal.find('#modalEstValide').text(estValide);
        modal.find('#modalCreatedAt').text(createdAt);
        modal.find('#modalUpdatedAt').text(updatedAt);
    });

    // Nouveau script pour les icônes cliquables d'activation/désactivation
    $(document).ready(function() {
        $('.toggle-validation-icon').on('click', function(e) {
            e.preventDefault(); // Empêche le comportement par défaut du lien

            var $link = $(this); // Le lien <a> cliqué
            var evenementId = $link.data('id');
            var currentEstValide = $link.data('estvalide') === 1; // Convertir en boolean

            var url = '{{ route("admin.evenements.toggleValidation", ":id") }}';
            url = url.replace(':id', evenementId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                    // Pas besoin d'envoyer 'estActif' car le contrôleur l'inverse.
                    // Si le contrôleur avait besoin de l'état ciblé, on enverrait estActif: !currentEstActif
                },
                success: function(response) {
                    if (response.success) {
                        showTemporaryAlert(response.message, 'success');

                        // Mettre à jour l'icône et l'attribut data-estactif
                        var $icon = $('#icon-' + evenementId);
                        if (response.estValide) { // Si l'événement est maintenant ACTIF
                            $icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                            $link.data('estvalide', 1); // Met à jour l'état dans les données du lien
                            $link.attr('title', 'Désactiver l\'événement'); // Met à jour l'infobulle
                        } else { // Si l'événement est maintenant INACTIF
                            $icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                            $link.data('estvalide', 0); // Met à jour l'état dans les données du lien
                            $link.attr('title', 'Valider l\'événement'); // Met à jour l'infobulle
                        }
                    } else {
                        showTemporaryAlert('Erreur lors de la mise à jour du statut.', 'danger');
                    }
                },
                error: function(xhr) {
                    console.error('Erreur AJAX:', xhr.responseText);
                    showTemporaryAlert('Une erreur est survenue lors de la mise à jour du statut.', 'danger');
                }
            });
        });
    });
</script>
@endpush

@push('styles')
{{-- Styles CSS pour Font Awesome (à inclure dans votre layout principal de préférence) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
{{-- Les styles du toggle switch ne sont plus nécessaires et peuvent être supprimés --}}
@endpush

@push('styles')
{{-- Styles CSS pour Font Awesome (à inclure dans votre layout principal de préférence) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

{{-- Les styles CSS pour le Toggle Switch NE SONT PLUS NÉCESSAIRES si vous utilisez des icônes pour le statut --}}
{{-- et peuvent être supprimés de cette section. --}}
@endpush