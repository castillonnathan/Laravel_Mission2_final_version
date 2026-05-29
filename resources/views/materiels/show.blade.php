<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $materiel->nom }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('materiels.edit', $materiel) }}"
                   class="px-4 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition">Modifier</a>
                <a href="{{ route('materiels.index') }}"
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">← Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-4">

            {{-- Alerte stock --}}
            @if($materiel->quantite_stock === 0)
                <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    Ce matériel est en <strong>rupture de stock</strong>.
                </div>
            @elseif($materiel->en_alerte)
                <div class="p-4 bg-orange-100 border border-orange-300 text-orange-800 rounded-lg">
                    Stock sous le seuil d'alerte ({{ $materiel->quantite_stock }} / {{ $materiel->seuil_alerte }}).
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-4 text-sm">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs uppercase text-gray-400">ID</p>
                        <p class="font-medium text-gray-800 dark:text-white mt-1">{{ $materiel->id_materiel }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">Nom</p>
                        <p class="font-medium text-gray-800 dark:text-white mt-1">{{ $materiel->nom }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">Quantité en stock</p>
                        <p class="font-bold text-2xl mt-1 {{ $materiel->quantite_stock === 0 ? 'text-red-600' : ($materiel->en_alerte ? 'text-orange-500' : 'text-green-600') }}">
                            {{ $materiel->quantite_stock }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">Seuil d'alerte</p>
                        <p class="font-bold text-2xl mt-1 text-gray-700 dark:text-gray-300">{{ $materiel->seuil_alerte }}</p>
                    </div>
                </div>
                @if($materiel->description)
                    <div>
                        <p class="text-xs uppercase text-gray-400">Description</p>
                        <p class="mt-1 text-gray-700 dark:text-gray-300 leading-relaxed">{{ $materiel->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
