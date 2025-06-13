<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Organisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Bienvenue Organisateur</h1>

        <p class="mb-4">Bienvenue, {{ Auth::user()->firstName }} !</p>

        <a href="{{ route('organisateur.evenements.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            🎉 Créer un nouvel événement
        </a>

        <a href="{{ route('organisateur.evenements.index') }}"
           class="ml-4 text-blue-700 underline hover:text-blue-900">
            Voir mes événements
        </a>
    </div>
</body>
</html>
