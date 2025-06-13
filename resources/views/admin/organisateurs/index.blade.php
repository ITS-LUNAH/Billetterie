<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des organisateurs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Liste des organisateurs</h1>

    {{-- Affichage des messages de succès --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @foreach ($organisateurs as $org)
        <div class="bg-white p-4 rounded shadow mb-4">
            <strong>{{ $org->societe }}</strong> – ICE : {{ $org->ICE }}<br>
            Statut :
            @if ($org->estValide)
                <span class="text-green-600">Validé</span>
            @else
                <span class="text-yellow-600">En attente</span>
            @endif
            |
            @if ($org->estActif)
                <span class="text-blue-600">Actif</span>
            @else
                <span class="text-red-600">Suspendu</span>
            @endif

            {{-- Formulaire de validation --}}
            @if (!$org->estValide)
                <form method="POST" action="{{ route('admin.organisateurs.valider', $org->idOrganisateur) }}">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-1 mt-2 rounded hover:bg-green-600">
                        Valider
                    </button>
                </form>
            @endif

            {{-- Formulaire de suspension ou de réactivation --}}
            @if ($org->estActif)
                <form method="POST" action="{{ route('admin.organisateurs.suspendre', $org->idOrganisateur) }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-1 mt-2 rounded hover:bg-red-600">
                        Suspendre
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.organisateurs.reactiver', $org->idOrganisateur) }}">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 mt-2 rounded hover:bg-blue-600">
                        Réactiver
                    </button>
                </form>
            @endif
        </div>
    @endforeach
</body>
</html>
