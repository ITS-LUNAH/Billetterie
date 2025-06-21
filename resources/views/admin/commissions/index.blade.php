<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des commissions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Liste des Commissions</h1>

    <a href="{{ route('admin.commissions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">
        ➕ Ajouter une commission
    </a>

    @if ($commissions->isEmpty())
        <p class="text-gray-600">Aucune commission définie.</p>
    @else
        <table class="w-full bg-white rounded shadow overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="text-left px-4 py-2">ID</th>
                    <th class="text-left px-4 py-2">Type</th>
                    <th class="text-left px-4 py-2">Montant (%)</th>
                    <th class="text-left px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commissions as $commission)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $commission->id }}</td>
                        <td class="px-4 py-2 capitalize">{{ $commission->type }}</td>
                        <td class="px-4 py-2">{{ $commission->montant }} %</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.commissions.edit', $commission->id) }}" class="text-blue-600 hover:underline mr-2">Modifier</a>
                            <form method="POST" action="{{ route('admin.commissions.destroy', $commission->id) }}" class="inline-block" onsubmit="return confirm('Supprimer cette commission ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
