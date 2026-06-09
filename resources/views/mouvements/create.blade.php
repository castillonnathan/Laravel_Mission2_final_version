<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Nouveau mouvement</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <form method="POST" action="{{ route('mouvements.store') }}"
                  class="bg-white shadow-sm sm:rounded-lg p-6 space-y-5"
                  id="form-mouvement">
                @csrf

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de mouvement *</label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach(\App\Models\Mouvement::TYPES as $val => $lbl)
                            @php
                                $colors = [
                                    'entree'     => 'bg-green-100 text-green-700 border-green-300',
                                    'sortie'     => 'bg-red-100 text-red-700 border-red-300',
                                    'transfert'  => 'bg-blue-100 text-blue-700 border-blue-300',
                                    'ajustement' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                ];
                            @endphp
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer
                                          {{ $colors[$val] }} has-[:checked]:ring-2 has-[:checked]:ring-offset-1">
                                <input type="radio" name="type" value="{{ $val }}"
                                       @checked(old('type', $type) === $val)
                                       class="sr-only" onchange="updateUI()">
                                {{ $lbl }}
                            </label>
                        @endforeach
                    </div>
                    @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                    {{-- Info transfert --}}
                    <div id="info-transfert"
                         class="mt-2 text-xs text-blue-700 bg-blue-50 border border-blue-200 rounded p-2 hidden">
                        À la création, le statut sera automatiquement <strong>Transfert en cours</strong>.
                        Un admin ou technicien pourra le passer à <strong>Transfert terminé</strong> depuis la fiche.
                    </div>
                </div>

                {{-- Minerai + Quantité --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Minerai *</label>
                        <select name="minerai_id" id="select-minerai" required
                                onchange="updateSiteOptions()"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Choisir —</option>
                            @foreach($minerais as $m)
                                <option value="{{ $m->id }}" @selected(old('minerai_id') == $m->id)>
                                    {{ $m->nom }} ({{ $m->unite }})
                                </option>
                            @endforeach
                        </select>
                        @error('minerai_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantité *</label>
                        <input type="number" name="quantite" step="0.001"
                               value="{{ old('quantite') }}"
                               placeholder="0.000"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <p class="text-xs text-gray-400 mt-1">Peut être négatif pour un ajustement correctif.</p>
                        @error('quantite') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Sites --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="bloc-source">
                        <label class="block text-sm font-medium text-gray-700">Site source</label>
                        <select name="site_source_id" id="select-source"
                                onchange="updateStockInfo('source')"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Aucun —</option>
                            @foreach($sites as $s)
                                <option value="{{ $s->id }}" @selected(old('site_source_id') == $s->id)>
                                    {{ $s->nom }} ({{ str_replace('_', ' ', $s->type) }})
                                </option>
                            @endforeach
                        </select>
                        <p id="info-source" class="text-xs mt-1 hidden"></p>
                        @error('site_source_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div id="bloc-destination">
                        <label class="block text-sm font-medium text-gray-700">Site destination</label>
                        <select name="site_destination_id" id="select-destination"
                                onchange="updateStockInfo('destination')"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">— Aucun —</option>
                            @foreach($sites as $s)
                                <option value="{{ $s->id }}" @selected(old('site_destination_id') == $s->id)>
                                    {{ $s->nom }} ({{ str_replace('_', ' ', $s->type) }})
                                </option>
                            @endforeach
                        </select>
                        <p id="info-destination" class="text-xs mt-1 hidden"></p>
                        @error('site_destination_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Aide contextuelle --}}
                <div id="aide-type" class="text-xs text-gray-500 bg-gray-50 rounded p-3 hidden"></div>

                {{-- Motif --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Motif / Observations</label>
                    <textarea name="motif" rows="2"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                              placeholder="Optionnel — utile pour les ajustements">{{ old('motif') }}</textarea>
                    @error('motif') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date du mouvement</label>
                    <input type="datetime-local" name="date_mouvement"
                           value="{{ old('date_mouvement', now()->format('Y-m-d\TH:i')) }}"
                           class="mt-1 block w-full md:w-auto border-gray-300 rounded-md shadow-sm">
                    @error('date_mouvement') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2 justify-end pt-2 border-t">
                    <a href="{{ route('mouvements.index') }}"
                       class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Enregistrer le mouvement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Données injectées depuis PHP
        const sitesMineraisAutorises = @json($sitesMineraisAutorises); // {} ou [id,id,...] pour les mines
        const stocksParSite          = @json($stocksParSite);
        const sitesSontMine          = @json($sitesSontMine);           // { siteId: true/false }

        const aides = {
            entree:     'Entrée : renseignez uniquement le site de destination.',
            sortie:     'Sortie : renseignez uniquement le site source.',
            transfert:  'Transfert : renseignez le site source ET le site de destination.',
            ajustement: 'Ajustement : renseignez le site concerné comme destination.',
        };

        function getType() {
            return document.querySelector('input[name="type"]:checked')?.value;
        }

        function getMineraiId() {
            return parseInt(document.getElementById('select-minerai').value) || null;
        }

        function updateUI() {
            const type = getType();

            // Aide contextuelle
            const aideEl = document.getElementById('aide-type');
            if (aides[type]) {
                aideEl.textContent = aides[type];
                aideEl.classList.remove('hidden');
            } else {
                aideEl.classList.add('hidden');
            }

            // Info transfert automatique
            document.getElementById('info-transfert').classList.toggle('hidden', type !== 'transfert');

            updateSiteOptions();
        }

        /**
         * Filtre les options des selects de site :
         * - Si c'est une mine ET que le minerai n'y est pas autorisé → option désactivée
         * - Dépôt / client / autre → jamais désactivé
         */
        function updateSiteOptions() {
            const mineraiId = getMineraiId();

            ['select-source', 'select-destination'].forEach(id => {
                const sel = document.getElementById(id);
                Array.from(sel.options).forEach(opt => {
                    if (!opt.value) return;
                    const siteId  = parseInt(opt.value);
                    const estMine = sitesSontMine[siteId] ?? false;

                    if (!estMine || !mineraiId) {
                        // Pas une mine ou pas encore de minerai choisi → toujours autorisé
                        opt.disabled = false;
                        opt.title    = '';
                    } else {
                        // Mine : vérifier la liste des minerais autorisés
                        const autorises = sitesMineraisAutorises[siteId] ?? [];
                        if (autorises.length === 0 || autorises.includes(mineraiId)) {
                            opt.disabled = false;
                            opt.title    = '';
                        } else {
                            opt.disabled = true;
                            opt.title    = '⛔ Ce minerai n\'est pas exploitable sur cette mine';
                            if (opt.selected) opt.selected = false;
                        }
                    }
                });

                const role = id === 'select-source' ? 'source' : 'destination';
                updateStockInfo(role);
            });
        }

        function updateStockInfo(role) {
            const selId  = role === 'source' ? 'select-source' : 'select-destination';
            const infoId = `info-${role}`;
            const siteId    = parseInt(document.getElementById(selId).value) || null;
            const mineraiId = getMineraiId();
            const info      = document.getElementById(infoId);

            if (!siteId || !mineraiId) {
                info.classList.add('hidden');
                return;
            }

            const estMine  = sitesSontMine[siteId] ?? false;
            const autorises = sitesMineraisAutorises[siteId] ?? [];
            const stocks    = stocksParSite[siteId] ?? {};
            const qte       = parseFloat(stocks[mineraiId] ?? 0);

            // Avertissement minerai non autorisé sur mine
            if (estMine && autorises.length > 0 && !autorises.includes(mineraiId)) {
                info.textContent = 'Ce minerai n\'est pas exploitable sur cette mine. Le mouvement sera refusé.';
                info.className   = 'text-xs mt-1 text-red-600 font-medium';
                info.classList.remove('hidden');
                return;
            }

            // Info stock
            if (role === 'source') {
                info.textContent = qte > 0
                    ? `Stock disponible : ${qte.toFixed(3)}`
                    : 'Aucun stock de ce minerai sur ce site.';
                info.className = qte > 0
                    ? 'text-xs mt-1 text-green-600'
                    : 'text-xs mt-1 text-orange-500';
            } else {
                info.textContent = `Stock actuel sur ce site : ${qte.toFixed(3)}`;
                info.className   = 'text-xs mt-1 text-gray-500';
            }

            info.classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateUI();
        });
    </script>
</x-app-layout>
