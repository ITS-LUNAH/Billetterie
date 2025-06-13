<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des événements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Liste des événements</h1>

    @if($evenements->isEmpty())
        <p class="text-gray-600">Aucun événement à afficher.</p>
    @else
        @foreach ($evenements as $event)
            <div class="bg-white p-4 rounded shadow mb-4">
                <h2 class="text-lg font-semibold">{{ $event->titre }}</h2>
                <p><strong>Lieu :</strong> {{ $event->lieu }}</p>
                <p><strong>Organisateur :</strong> {{ $event->organisateur->societe ?? 'N/A' }}</p>
                <p><strong>Type :</strong> {{ $event->typeplacement }}</p>
                <p><strong>Validé ?</strong>
                    @if ($event->estvalide)
                        <span class="text-green-600">Oui</span>
                    @else
                        <span class="text-red-600">Non</span>
                    @endif
                </p>

                <div class="mt-3 flex gap-2">
                    @if (!$event->estvalide)
                        <form method="POST" action="{{ route('admin.evenements.valider', $event->idevenement) }}">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
                                Valider
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.evenements.refuser', $event->idevenement) }}">
                            @csrf
                            <button type="submit" class="bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600">
                                Refuser
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</body>
</html>
