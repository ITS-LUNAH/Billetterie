<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'événement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Modifier l'événement</h1>

    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('organisateur.evenements.update', $evenement->idevenement) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="titre" class="block font-semibold">Titre :</label>
            <input type="text" id="titre" name="titre" value="{{ old('titre', $evenement->titre) }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-semibold">Description :</label>
            <textarea id="description" name="description" class="w-full border px-3 py-2 rounded">{{ old('description', $evenement->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="lieu" class="block font-semibold">Lieu :</label>
            <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="bandeAnnonce" class="block font-semibold">Bande Annonce (Image) :</label>
            @if($evenement->bandeannonce)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $evenement->bandeannonce) }}" alt="Image actuelle" class="w-32 h-32 object-cover rounded">
                    <p class="text-sm text-gray-600">Image actuelle</p>
                </div>
            @endif
            <input type="file" id="bandeAnnonce" name="bandeAnnonce" accept="image/*" class="w-full border px-3 py-2 rounded">
            <p class="text-sm text-gray-600">Laissez vide pour conserver l'image actuelle</p>
        </div>

        <div class="mb-4">
            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie" required class="...">
                <option value="">-- Sélectionner --</option>
                <option value="Concert">Concert</option>
                <option value="Théâtre">Théâtre</option>
                <option value="Festival">Festival</option>
                <option value="Conférence">Conférence</option>
            </select>

        </div>

        <div class="mb-4">
            <label for="prixBase" class="block font-semibold">Prix de base :</label>
            <input type="number" step="0.01" id="prixBase" name="prixBase" value="{{ old('prixBase', $evenement->prixbase) }}" class="w-full border px-3 py-2 rounded">
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Modifier
            </button>
            
            <a href="{{ route('organisateur.evenements.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Annuler
            </a>
        </div>
    </form>
</div>
</body>
</html>