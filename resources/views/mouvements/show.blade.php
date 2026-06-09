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

                {{-- En-tête --}}
                <div class="flex items-center gap-3 pb-4 border-b flex-wrap">
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

                    {{-- Badge statut transfert uniquement --}}
                    @if($mouvement->type === \App\Models\Mouvement::TYPE_TRANSFERT)
                        @if($mouvement->transfert_en_cours)
                            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full
                                         bg-amber-100 text-amber-800 border border-amber-300 font-medium">
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                Transfert en cours
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full
                                         bg-green-100 text-green-800 border border-green-300 font-medium">
                                ✓ Transfert terminé
                            </span>
                        @endif
                    @endif
                </div>

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

                    {{-- Infos supplémentaires pour les transferts --}}
                    @if($mouvement->type === \App\Models\Mouvement::TYPE_TRANSFERT)
                        <div>
                            <dt class="text-gray-400 text-xs uppercase">Date de clôture</dt>
                            <dd class="mt-1">
                                @if($mouvement->date_fin)
                                    {{ $mouvement->date_fin->format('d/m/Y à H:i') }}
                                @else
                                    <span class="text-amber-600 italic text-xs">Non clôturé</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 text-xs uppercase">Durée du transfert</dt>
                            <dd class="mt-1 font-medium">
                                {{ $mouvement->getDureeAttribute() ?? '—' }}
                                @if($mouvement->transfert_en_cours)
                                    <span class="text-xs text-amber-500 font-normal">(en cours)</span>
                                @endif
                            </dd>
                        </div>
                    @endif

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

                <div class="pt-4 border-t flex flex-wrap gap-3 justify-end">

                    {{-- Clôturer un transfert en cours : admin ou technicien --}}
                    @if($mouvement->type === \App\Models\Mouvement::TYPE_TRANSFERT && $mouvement->transfert_en_cours)
                        @can('cloturer-transfert')
                        @else
                        @endcan
                        {{-- Accessible à admin & technicien (protégé par la route) --}}
                        <form method="POST"
                              action="{{ route('mouvements.cloturer', $mouvement) }}"
                              onsubmit="return confirm('Marquer ce transfert comme terminé ?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700">
                                Marquer le transfert comme terminé
                            </button>
                        </form>
                    @endif

                    {{-- Suppression : admin uniquement --}}
                    @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('mouvements.destroy', $mouvement) }}"
                              onsubmit="return confirm('Supprimer ce mouvement ? Le stock sera recalculé.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 border border-red-300 px-3 py-2 rounded-md text-sm hover:bg-red-50">
                                Supprimer ce mouvement
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
