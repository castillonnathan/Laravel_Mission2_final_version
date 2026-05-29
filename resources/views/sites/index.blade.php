<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sites</h2>
            <a href="{{ route('sites.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                + Nouveau site
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($sites->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun site enregistré.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Ville</th>
                            <th class="px-4 py-3">Actif</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($sites as $site)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $site->nom }}</td>
                                <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $site->type) }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $site->ville ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if($site->actif)
                                        <span class="text-green-600 text-xs">Oui</span>
                                    @else
                                        <span class="text-gray-400 text-xs">Non</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex gap-3 items-center">
                                    <a href="{{ route('sites.edit', $site) }}"
                                       class="text-blue-600 hover:underline text-sm">Modifier</a>
                                    <form method="POST" action="{{ route('sites.destroy', $site) }}"
                                          onsubmit="return confirm('Supprimer ce site ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:underline text-sm">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
