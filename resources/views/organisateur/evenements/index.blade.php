<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/indexOrganisateur.css') }}">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Mes Événements</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('organisateur.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium rounded-lg shadow-lg hover:from-purple-600 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300 ease-in-out hover:shadow-xl">
               <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
               </svg>
               Retour au Dashboard
            </a>
            
            <a href="{{ route('organisateur.evenements.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-medium rounded-lg shadow-lg hover:from-blue-600 hover:to-cyan-700 transform hover:scale-105 transition-all duration-300 ease-in-out hover:shadow-xl">
               <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
               </svg>
               Créer un événement
            </a>
        </div>

        @if ($evenements->isEmpty())
            <p class="text-gray-600">Aucun événement pour le moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded shadow overflow-hidden">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="text-left px-4 py-2">Titre</th>
                            <th class="text-left px-4 py-2">Lieu</th>
                            <th class="text-left px-4 py-2">Catégorie</th>
                            <th class="text-left px-4 py-2">Type de placement</th>
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
                                <td class="px-4 py-2 capitalize">{{ $event->categorie }}</td>
                                <td class="px-4 py-2">
                                    @if($event->typeplacement)
                                        @switch($event->typeplacement)
                                            @case('libre')
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Libre</span>
                                                @break
                                            @case('numerote')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Numéroté</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ ucfirst($event->typeplacement) }}</span>
                                        @endswitch
                                    @else
                                        <span class="text-gray-400 text-xs">Non défini</span>
                                    @endif
                                </td>
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
            </div>
        @endif
    </div>
</body>
</html>