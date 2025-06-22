@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestion des Organisateurs</h1>

    {{-- Temporary success/error messages --}}
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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Organisateurs</h6>
            {{-- As per Cahier des charges, admin validates, not creates, so removing the "Ajouter" button if it exists --}}
            {{-- <a href="{{ route('admin.organisateurs.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Ajouter un organisateur
            </a> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="organisateursTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Société</th>
                            <th>Gérant</th>
                            <th>Email</th>
                            <th>ICE</th>
                            <th>Statut Validation</th>
                            <th>Statut Activité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organisateurs as $organisateur)
                        <tr>
                            <td>{{ $organisateur->idOrganisateur }}</td>
                            <td>{{ $organisateur->societe }}</td>
                            <td>{{ $organisateur->nomComplet }}</td>
                            <td>{{ $organisateur->user->email ?? 'N/A' }}</td>
                            <td>{{ $organisateur->ICE ?? 'N/A' }}</td>
                            <td>
                                {{-- Validation Status --}}
                                @if ($organisateur->estValide)
                                    <span class="badge badge-success">Validé</span>
                                    <form action="{{ route('admin.organisateurs.refuser', $organisateur->idOrganisateur) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cet organisateur ?');">
                                        @csrf
                                        {{-- Using the times icon as requested --}}
                                        <button type="submit" class="btn btn-sm btn-danger ml-1" title="Refuser"><i class="fas fa-times"></i></button>
                                    </form>
                                @else
                                    <span class="badge badge-warning">En attente / Refusé</span>
                                    <form action="{{ route('admin.organisateurs.valider', $organisateur->idOrganisateur) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir valider cet organisateur ?');">
                                        @csrf
                                        {{-- Using the check icon as requested --}}
                                        <button type="submit" class="btn btn-sm btn-success ml-1" title="Valider"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif
                            </td>
                            <td>
                               {{-- Activity Status Toggle --}}
                                <a href="javascript:void(0)" class="toggle-activite-organisateur-icon" {{-- Changed this class --}}
                                    data-id="{{ $organisateur->idOrganisateur }}"
                                    data-estactif="{{ $organisateur->estActif ? '1' : '0' }}" {{-- Stocke l'état actuel --}}
                                    title="{{ $organisateur->estActif ? 'Désactiver l\'organisateur' : 'Activer l\'organisateur' }}">
                                    @if ($organisateur->estActif)
                                        <i class="fas fa-toggle-on fa-2x text-success" id="organisateur-icon-{{ $organisateur->idOrganisateur }}"></i> {{-- Changed this ID --}}
                                    @else
                                        <i class="fas fa-toggle-off fa-2x text-danger" id="organisateur-icon-{{ $organisateur->idOrganisateur }}"></i> {{-- Changed this ID --}}
                                    @endif
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm mb-1" data-toggle="modal" data-target="#organisateurDetailModal"
                                    data-id="{{ $organisateur->idOrganisateur }}"
                                    data-societe="{{ $organisateur->societe }}"
                                    data-gerant="{{ $organisateur->nomComplet }}"
                                    data-email="{{ $organisateur->user->email ?? 'N/A' }}" {{-- Use user's email if available --}}
                                    data-ice="{{ $organisateur->ICE }}"
                                    data-secteur="{{ $organisateur->secteurActivite }}"
                                    data-estvalide="{{ $organisateur->estValide ? 'Validé' : 'En attente / Refusé' }}"
                                    data-estactif="{{ $organisateur->estActif ? 'Actif' : 'Suspendu' }}"
                                    data-created-at="{{ $organisateur->created_at ? $organisateur->created_at->format('d/m/Y à H:i') : 'N/A' }}"
                                    data-updated-at="{{ $organisateur->updated_at ? $organisateur->updated_at->format('d/m/Y à H:i') : 'N/A' }}"
                                    title="Visualiser les détails">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <a href="{{ route('admin.organisateurs.edit', $organisateur->idOrganisateur) }}" class="btn btn-warning btn-sm mb-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.organisateurs.destroy', $organisateur->idOrganisateur) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet organisateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer">
                                        <i class="fas fa-trash"></i>
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

{{-- Modale de détails (keep as is) --}}
<div class="modal fade" id="organisateurDetailModal" tabindex="-1" role="dialog" aria-labelledby="organisateurDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organisateurDetailModalLabel">Détails de l'Organisateur #<span id="modalOrganisateurId"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Informations personnelles</h4>
                        <p><strong>Société :</strong> <span id="modalSociete"></span></p>
                        <p><strong>Gérant :</strong> <span id="modalGerant"></span></p>
                        <p><strong>Email :</strong> <span id="modalEmail"></span></p>
                        <p><strong>ICE :</strong> <span id="modalICE"></span></p>
                        <p><strong>Secteur d'Activité :</strong> <span id="modalSecteur"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Statuts</h4>
                        <p><strong>Validation :</strong> <span id="modalEstValide"></span></p>
                        <p><strong>Activité :</strong> <span id="modalEstActif"></span></p>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script>
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
        }, 5000); // Alert disappears after 5 seconds
    }

    $('#organisateurDetailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var societe = button.data('societe');
        var gerant = button.data('gerant');
        var email = button.data('email');
        var ice = button.data('ice');
        var secteur = button.data('secteur');
        var estValide = button.data('estvalide');
        var estActif = button.data('estactif');
        var createdAt = button.data('created-at');
        var updatedAt = button.data('updated-at');

        var modal = $(this);
        modal.find('#modalOrganisateurId').text(id);
        modal.find('#modalSociete').text(societe);
        modal.find('#modalGerant').text(gerant);
        modal.find('#modalEmail').text(email);
        modal.find('#modalICE').text(ice);
        modal.find('#modalSecteur').text(secteur);
        modal.find('#modalEstValide').text(estValide);
        modal.find('#modalEstActif').text(estActif);
        modal.find('#modalCreatedAt').text(createdAt);
        modal.find('#modalUpdatedAt').text(updatedAt);
    });

    $(document).ready(function() {
        // Initialize DataTables with Bootstrap 4 styling
        $('#organisateursTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
            },
            "paging": true,
            "searching": true, // Enable search bar
            "info": true,
            "ordering": true,
            "columnDefs": [
                { "orderable": false, "targets": [5, 6, 7] } // Disable ordering for Validation, Activity, and Actions columns
            ]
        });

        // AJAX for activity toggle
        $('.toggle-activite-organisateur-icon').on('click', function(e) {
            e.preventDefault();

            var $link = $(this);
            var organisateurId = $link.data('id');
            var currentEstActif = $link.data('estactif') === 1;

            var url;
            if (currentEstActif) {
                // If currently active, we want to suspend it
                url = '{{ route("admin.organisateurs.suspendre", ":id") }}';
            } else {
                // If currently suspended, we want to reactivate it
                url = '{{ route("admin.organisateurs.reactiver", ":id") }}';
            }
            url = url.replace(':id', organisateurId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showTemporaryAlert(response.message, 'success');

                        var $icon = $('#organisateur-icon-' + organisateurId);
                        if (!currentEstActif) { // If it was suspended, now it's active
                            $icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                            $link.data('estactif', 1);
                            $link.attr('title', 'Suspendre l\'organisateur');
                        } else { // If it was active, now it's suspended
                            $icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                            $link.data('estactif', 0);
                            $link.attr('title', 'Réactiver l\'organisateur');
                        }
                    } else {
                        showTemporaryAlert(response.message || 'Erreur lors de la mise à jour du statut.', 'danger');
                    }
                },
                error: function(xhr) {
                    console.error('Erreur AJAX:', xhr.responseText);
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Une erreur est survenue lors de la mise à jour du statut.';
                    showTemporaryAlert(errorMessage, 'danger');
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css"/>
<style>
    /* Custom styling for DataTables search input to make it look nicer */
    .dataTables_filter input {
        border-radius: .25rem;
        border: 1px solid #ced4da;
        padding: .375rem .75rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .dataTables_filter input:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }
    /* Align search and entries dropdown */
    .dataTables_wrapper .row:first-child {
        align-items: center;
    }
    /* Styles for the toggle switch icons */
    .toggle-activite-organisateur-icon {
        cursor: pointer;
        display: inline-block; /* Allows vertical alignment and margin */
        vertical-align: middle;
    }
    .toggle-activite-organisateur-icon i {
        /* Ensure Font Awesome icons are sized correctly */
        line-height: 1; /* Adjust if needed */
    }
</style>
@endpush