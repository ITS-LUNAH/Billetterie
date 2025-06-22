@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Modifier l'Événement : {{ $evenement->titre }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.evenements.update', $evenement->idevenement) }}" method="POST">
                @csrf
                @method('PUT') {{-- Utiliser la méthode PUT pour la mise à jour --}}

                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre', $evenement->titre) }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $evenement->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="dateEvenement">Date et Heure de l'événement</label>
                    <input type="datetime-local" class="form-control" id="dateEvenement" name="dateEvenement" value="{{ old('dateEvenement', \Carbon\Carbon::parse($evenement->dateEvenement)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group">
                    <label for="lieu">Lieu</label>
                    <input type="text" class="form-control" id="lieu" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" required>
                </div>

                <div class="form-group">
                    <label for="capacite">Capacité</label>
                    <input type="number" class="form-control" id="capacite" name="capacite" value="{{ old('capacite', $evenement->capacite) }}" required min="1">
                </div>

                <div class="form-group">
                    <label for="organisateur_id">Organisateur</label>
                    <select class="form-control" id="organisateur_id" name="organisateur_id" required>
                        @foreach($organisateurs as $organisateur)
                            <option value="{{ $organisateur->id }}" {{ $evenement->organisateur_id == $organisateur->id ? 'selected' : '' }}>
                                {{ $organisateur->nomComplet }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="estActif">Statut Actif</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="estActif" name="estActif" value="1" {{ old('estActif', $evenement->estActif) ? 'checked' : '' }}>
                        <label class="form-check-label" for="estActif">Actif</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour l'événement</button>
                <a href="{{ route('admin.evenements.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection