<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un événement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/createEvent.css') }}">
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Créer un événement</h1>

    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('organisateur.evenements.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="titre" class="block font-semibold">Titre :</label>
            <input type="text" id="titre" name="titre" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-semibold">Description :</label>
            <textarea id="description" name="description" class="w-full border px-3 py-2 rounded"></textarea>
        </div>

        <div class="mb-4">
            <label for="lieu" class="block font-semibold">Lieu :</label>
            <input type="text" id="lieu" name="lieu" class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="bandeAnnonce" class="block font-semibold">Bande Annonce (Image) :</label>
            <input type="file" id="bandeAnnonce" name="bandeAnnonce" accept="image/*" class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label for="categorie" class="block font-semibold">Catégorie :</label>
            <select name="categorie" id="categorie" required class="w-full border px-3 py-2 rounded">
                <option value="">-- Sélectionner --</option>
                <option value="Concert">Concert</option>
                <option value="Théâtre">Théâtre</option>
                <option value="Festival">Festival</option>
                <option value="Conférence">Conférence</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="typeplacement" class="block font-semibold">Type de placement :</label>
            <select name="typeplacement" id="typeplacement" class="w-full border px-3 py-2 rounded">
                <option value="">-- Sélectionner --</option>
                <option value="libre">Placement libre</option>
                <option value="numerote">Placement numéroté</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="prixBase" class="block font-semibold">Prix de base :</label>
            <input type="number" step="0.01" id="prixBase" name="prixBase" class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Créer
        </button>
    </form>
</div>
</body>
</html>