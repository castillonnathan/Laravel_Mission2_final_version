<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Gestion des Matériels
            </h2>
            <a href="{{ route('materiels.create') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                + Ajouter un matériel
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="p-4 text-green-800 bg-green-100 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['total'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total matériels</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['stock'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Unités en stock</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <p class="text-3xl font-bold text-orange-500">{{ $stats['alertes'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">En alerte</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $stats['ruptures'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">En rupture</p>
                </div>
            </div>

            {{-- Filtres --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <form method="GET" action="{{ route('materiels.index') }}" class="flex flex-wrap gap-3 items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Rechercher par nom ou description..."
                           class="flex-1 min-w-[220px] rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 cursor-pointer">
                        <input type="checkbox" name="alerte" value="oui" {{ request('alerte') === 'oui' ? 'checked' : '' }}
                        class="rounded border-gray-300 text-orange-500">
                        Afficher seulement les alertes
                    </label>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                        Filtrer
                    </button>
                    @if(request()->hasAny(['search','alerte']))
                        <a href="{{ route('materiels.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-600 text-sm rounded-lg hover:bg-gray-200 transition">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Tableau --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <!-- <th class="px-6 py-3">#</th> -->
                        <th class="px-6 py-3">Nom</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3 text-center">Stock</th>
                        <th class="px-6 py-3 text-center">Seuil alerte</th>
                        <th class="px-6 py-3 text-center">État</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($materiels as $materiel)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition {{ $materiel->en_alerte ? 'border-l-4 border-orange-400' : '' }}">
                            <!-- <td class="px-6 py-4 text-gray-400 text-xs">{{ $materiel->id_materiel }}</td> -->
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $materiel->nom }}</td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $materiel->description ?? '—' }}</td>
                            <td class="px-6 py-4 text-center font-bold
                                {{ $materiel->quantite_stock === 0 ? 'text-red-600' : ($materiel->en_alerte ? 'text-orange-500' : 'text-green-600') }}">
                                {{ $materiel->quantite_stock }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $materiel->seuil_alerte }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($materiel->quantite_stock === 0)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Rupture</span>
                                @elseif($materiel->en_alerte)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">⚠ Alerte</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">OK</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('materiels.show', $materiel) }}" class="text-indigo-600 hover:underline text-xs">Voir</a>
                                    <a href="{{ route('materiels.edit', $materiel) }}" class="text-yellow-600 hover:underline text-xs">Modifier</a>
                                    <form method="POST" action="{{ route('materiels.destroy', $materiel) }}"
                                          onsubmit="return confirm('Supprimer « {{ $materiel->nom }} » ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">Aucun matériel trouvé.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $materiels->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
