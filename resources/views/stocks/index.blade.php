<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Stock de minerai</h2>
            <a href="{{ route('mouvements.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                + Nouveau mouvement
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            @if($sites->isEmpty() || $minerais->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500 text-sm">
                    @if($sites->isEmpty())
                        Aucun site actif. <a href="{{ route('sites.create') }}" class="text-blue-600 hover:underline">Créer un site</a>.
                    @else
                        Aucun minerai actif. <a href="{{ route('minerais.create') }}" class="text-blue-600 hover:underline">Créer un minerai</a>.
                    @endif
                </div>
            @else
                <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <th class="px-4 py-3 sticky left-0 bg-gray-50">Site</th>
                            @foreach($minerais as $m)
                                <th class="px-4 py-3 text-right whitespace-nowrap">
                                    {{ $m->nom }}
                                    <span class="block font-normal text-gray-400 normal-case">{{ $m->unite }}</span>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($sites as $site)
                            <tr>
                                <td class="px-4 py-3 font-medium sticky left-0 bg-white whitespace-nowrap">
                                    <div>{{ $site->nom }}</div>
                                    <div class="text-xs text-gray-400 capitalize">
                                        {{ str_replace('_', ' ', $site->type) }}
                                    </div>
                                </td>
                                @foreach($minerais as $m)
                                    @php
                                        $qte = $matrice[$site->id][$m->id] ?? 0;
                                    @endphp
                                    <td class="px-4 py-3 text-right font-mono
                                                   {{ $qte > 0 ? 'text-gray-800' : 'text-gray-300' }}">
                                        {{ $qte > 0
                                            ? number_format($qte, 3, ',', ' ')
                                            : '—' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                        {{-- Ligne de total --}}
                        <tfoot>
                        <tr class="bg-gray-50 font-semibold text-gray-700 border-t-2">
                            <td class="px-4 py-3 sticky left-0 bg-gray-50">Total</td>
                            @foreach($minerais as $m)
                                <td class="px-4 py-3 text-right font-mono">
                                    {{ number_format($totauxParMinerai[$m->id] ?? 0, 3, ',', ' ') }}
                                </td>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Dernière mise à jour : {{ now()->format('d/m/Y à H:i') }}
                    — Les quantités sont synchronisées en temps réel avec chaque mouvement enregistré.
                </p>
            @endif
        </div>
    </div>
</x-app-layout>
