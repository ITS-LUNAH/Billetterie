@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Modifier l'Organisateur : {{ $organisateur->societe }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.organisateurs.update', $organisateur->idOrganisateur) }}" method="POST">
                @csrf
                @method('PUT') {{-- N'oubliez pas la méthode PUT pour la mise à jour --}}

                <div class="form-group">
                    <label for="societe">Société</label>
                    <input type="text" class="form-control @error('societe') is-invalid @enderror" id="societe" name="societe" value="{{ old('societe', $organisateur->societe) }}" required>
                    @error('societe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $organisateur->user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nomGerant">Nom du Gérant</label>
                    <input type="text" class="form-control @error('nomGerant') is-invalid @enderror" id="nomGerant" name="nomGerant" value="{{ old('nomGerant', $organisateur->nomGerant) }}" required>
                    @error('nomGerant')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="prenomGerant">Prénom du Gérant</label>
                    <input type="text" class="form-control @error('prenomGerant') is-invalid @enderror" id="prenomGerant" name="prenomGerant" value="{{ old('prenomGerant', $organisateur->prenomGerant) }}" required>
                    @error('prenomGerant')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="ICE">ICE</label>
                    <input type="text" class="form-control @error('ICE') is-invalid @enderror" id="ICE" name="ICE" value="{{ old('ICE', $organisateur->ICE) }}">
                    @error('ICE')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="secteurActivite">Secteur d'Activité</label>
                    <input type="text" class="form-control @error('secteurActivite') is-invalid @enderror" id="secteurActivite" name="secteurActivite" value="{{ old('secteurActivite', $organisateur->secteurActivite) }}">
                    @error('secteurActivite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Optionnel: Champ pour 'estValide' et 'estActif' si vous voulez les modifier via le formulaire --}}
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="estValide" name="estValide" value="1" {{ old('estValide', $organisateur->estValide) ? 'checked' : '' }}>
                    <label class="form-check-label" for="estValide">Validé</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="estActif" name="estActif" value="1" {{ old('estActif', $organisateur->estActif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="estActif">Actif</label>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour l'Organisateur</button>
                <a href="{{ route('admin.organisateurs.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection