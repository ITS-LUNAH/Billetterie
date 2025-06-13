<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Mes Événements</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('organisateur.evenements.create') }}"
           class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           ➕ Créer un événement
        </a>

        @if ($evenements->isEmpty())
            <p class="text-gray-600">Aucun événement pour le moment.</p>
        @else
            <table class="w-full bg-white rounded shadow overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="text-left px-4 py-2">Titre</th>
                        <th class="text-left px-4 py-2">Lieu</th>
                        <th class="text-left px-4 py-2">Type</th>
                        <th class="text-left px-4 py-2">Prix</th>
                        <th class="text-left px-4 py-2">Statut</th>
                        <th class="text-left px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evenements as $event)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $event->titre }}</td>
                            <td class="px-4 py-2">{{ $event->lieu }}</td>
                            <td class="px-4 py-2 capitalize">{{ $event->typeplacement }}</td>
                            <td class="px-4 py-2">{{ $event->prixbase ? number_format($event->prixbase, 2) . ' DH' : 'Gratuit' }}</td>
                            <td class="px-4 py-2">
                                @if ($event->estvalide)
                                    <span class="text-green-600 font-semibold">Validé</span>
                                @else
                                    <span class="text-yellow-600">En attente</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('organisateur.evenements.edit', $event->idevenement) }}" 
                                   class="text-blue-600 hover:underline mr-2">
                                   Modifier
                                </a>
                                |
                                <form action="{{ route('organisateur.evenements.destroy', $event->idevenement) }}" 
                                      method="POST" 
                                      class="inline ml-2"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>