<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Participant - {{ $participant->user->lastName ?? '' }} {{ $participant->user->firstName ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card { margin-top: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card-header { background-color:rgb(249, 213, 243); border-bottom: 1px solid #eee; }
        .btn-back { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Modifier Participant: {{ $participant->user->lastName ?? '' }} {{ $participant->user->firstName ?? '' }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.participants.update', $participant->idParticipant) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de l'utilisateur:</label>
                                <input type="text" class="form-control @error('lastName') is-invalid @enderror" id="nom" name="nom" value="{{ old('lastName', $participant->user->lastName ?? '') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom de l'utilisateur:</label>
                                <input type="text" class="form-control @error('firstName') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('firstName', $participant->user->firstName ?? '') }}" required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email de l'utilisateur:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $participant->user->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone de l'utilisateur:</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $participant->user->telephone ?? '') }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h5>Informations Bancaires du Participant</h5>

                            <div class="mb-3">
                                <label for="compteBancaire" class="form-label">Titulaire du Compte Bancaire:</label>
                                <input type="text" class="form-control @error('compteBancaire') is-invalid @enderror" id="compteBancaire" name="compteBancaire" value="{{ old('compteBancaire', $participant->compteBancaire ?? '') }}">
                                @error('compteBancaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="IBAN" class="form-label">IBAN:</label>
                                <input type="text" class="form-control @error('IBAN') is-invalid @enderror" id="IBAN" name="IBAN" value="{{ old('IBAN', $participant->IBAN ?? '') }}">
                                @error('IBAN')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="RIB" class="form-label">RIB:</label>
                                <input type="text" class="form-control @error('RIB') is-invalid @enderror" id="RIB" name="RIB" value="{{ old('RIB', $participant->RIB ?? '') }}">
                                @error('RIB')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>