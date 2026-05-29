<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Mouvements de minerai</h2>
            <a href="{{ route('mouvements.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                + Nouveau mouvement
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.flash')

            {{-- Filtres --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <form method="GET" class="flex gap-3 flex-wrap items-end">
                    <div>
                        <label class="block text-xs text-gray-600">Type</label>
                        <select name="type" class="border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Tous</option>
                            @foreach(\App\Models\Mouvement::TYPES as $val => $lbl)
                                <option value="{{ $val }}" @selected(request('type') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Minerai</label>
                        <select name="minerai_id" class="border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Tous</option>
                            @foreach($minerais as $m)
                                <option value="{{ $m->id }}" @selected(request('minerai_id') == $m->id)>
                                    {{ $m->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Site</label>
                        <select name="site_id" class="border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Tous</option>
                            @foreach($sites as $s)
                                <option value="{{ $s->id }}" @selected(request('site_id') == $s->id)>
                                    {{ $s->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="bg-gray-700 text-white px-3 py-1.5 rounded-md text-sm hover:bg-gray-800">
                        Filtrer
                    </button>
                    @if(request()->hasAny(['type', 'minerai_id', 'site_id']))
                        <a href="{{ route('mouvements.index') }}" class="text-sm text-gray-500 hover:underline self-center">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            {{-- Tableau --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if($mouvements->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun mouvement.</p>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Numéro</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Minerai</th>
                            <th class="px-4 py-3 text-right">Quantité</th>
                            <th class="px-4 py-3">Source → Destination</th>
                            <th class="px-4 py-3">Par</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($mouvements as $m)
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs">{{ $m->numero }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $m->date_mouvement->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $colors = [
                                            'entree'     => 'bg-green-100 text-green-700',
                                            'sortie'     => 'bg-red-100 text-red-700',
                                            'transfert'  => 'bg-blue-100 text-blue-700',
                                            'ajustement' => 'bg-yellow-100 text-yellow-700',
                                        ];
                                    @endphp
                                    <span class="text-xs px-2 py-1 rounded-full {{ $colors[$m->type] ?? '' }}">
                                            {{ $m->type_label }}
                                        </span>
                                </td>
                                <td class="px-4 py-3">{{ $m->minerai->nom ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-medium">
                                    {{ rtrim(rtrim(number_format($m->quantite, 3, ',', ' '), '0'), ',') }}
                                    <span class="text-gray-400 text-xs">{{ $m->minerai->unite ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $m->siteSource->nom ?? '—' }}
                                    <span class="text-gray-400">→</span>
                                    {{ $m->siteDestination->nom ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $m->user->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('mouvements.show', $m) }}"
                                       class="text-blue-600 hover:underline text-sm">Détail</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $mouvements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
