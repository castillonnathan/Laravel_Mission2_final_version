<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Mouvement {{ $mouvement->numero }}</h2>
            <a href="{{ route('mouvements.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Retour à la liste</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.flash')

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5">

                {{-- En-tête avec numéro et type --}}
                <div class="flex items-center gap-4 pb-4 border-b">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Numéro</p>
                        <p class="font-mono font-semibold text-lg">{{ $mouvement->numero }}</p>
                    </div>
                    @php
                        $colors = [
                            'entree'     => 'bg-green-100 text-green-700',
                            'sortie'     => 'bg-red-100 text-red-700',
                            'transfert'  => 'bg-blue-100 text-blue-700',
                            'ajustement' => 'bg-yellow-100 text-yellow-700',
                        ];
                    @endphp
                    <span class="text-sm px-3 py-1 rounded-full {{ $colors[$mouvement->type] ?? '' }}">
                        {{ $mouvement->type_label }}
                    </span>
                </div>

                {{-- Détails --}}
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Minerai</dt>
                        <dd class="font-medium mt-1">
                            {{ $mouvement->minerai->nom ?? '—' }}
                            <span class="text-gray-400">({{ $mouvement->minerai->code ?? '' }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Quantité</dt>
                        <dd class="font-semibold text-xl mt-1">
                            {{ number_format($mouvement->quantite, 3, ',', ' ') }}
                            <span class="text-sm font-normal text-gray-500">{{ $mouvement->minerai->unite ?? '' }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Site source</dt>
                        <dd class="mt-1">{{ $mouvement->siteSource->nom ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Site destination</dt>
                        <dd class="mt-1">{{ $mouvement->siteDestination->nom ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Date du mouvement</dt>
                        <dd class="mt-1">{{ $mouvement->date_mouvement->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Saisi par</dt>
                        <dd class="mt-1">{{ $mouvement->user->name ?? '—' }}</dd>
                    </div>
                    @if($mouvement->motif)
                        <div class="md:col-span-2">
                            <dt class="text-gray-400 text-xs uppercase">Motif / Observations</dt>
                            <dd class="mt-1 text-gray-700">{{ $mouvement->motif }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-gray-400 text-xs uppercase">Enregistré le</dt>
                        <dd class="mt-1 text-gray-500">{{ $mouvement->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                </dl>

                {{-- Suppression : admin uniquement --}}
                @if(auth()->user()->isAdmin())
                    <div class="pt-4 border-t flex justify-end">
                        <form method="POST" action="{{ route('mouvements.destroy', $mouvement) }}"
                              onsubmit="return confirm('Supprimer ce mouvement ? Le stock sera recalculé en conséquence.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 border border-red-300 px-3 py-1.5 rounded-md text-sm hover:bg-red-50">
                                Supprimer ce mouvement
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
