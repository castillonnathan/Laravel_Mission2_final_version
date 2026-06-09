{{-- Champs partagés entre create et edit --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom *</label>
        <input type="text" name="nom" required
               value="{{ old('nom', $site->nom ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('nom') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Type *</label>
        <select name="type" id="select-type" required
                onchange="toggleMineraisSection()"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach(['mine' => 'Mine', 'depot' => 'Dépôt', 'client_site' => 'Site client', 'autre' => 'Autre'] as $val => $lbl)
                <option value="{{ $val }}" @selected(old('type', $site->type ?? 'depot') === $val)>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
        @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Adresse</label>
        <input type="text" name="adresse"
               value="{{ old('adresse', $site->adresse ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Ville</label>
        <input type="text" name="ville"
               value="{{ old('ville', $site->ville ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Code postal</label>
        <input type="text" name="code_postal"
               value="{{ old('code_postal', $site->code_postal ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Pays</label>
        <input type="text" name="pays"
               value="{{ old('pays', $site->pays ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Notes</label>
    <textarea name="notes" rows="3"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $site->notes ?? '') }}</textarea>
</div>

{{-- ── Section minerais exploitables (visible uniquement pour les mines) ── --}}
<div id="section-minerais" class="border border-yellow-200 rounded-lg p-4 bg-yellow-50 hidden">
    <div class="flex items-center gap-2 mb-1">
        <span class="text-base">⛏️</span>
        <p class="text-sm font-semibold text-yellow-800">Minerais exploitables sur cette mine</p>
    </div>
    <p class="text-xs text-yellow-700 mb-3">
        Cochez les minerais que ce site minier est autorisé à manipuler.
        Tout mouvement impliquant cette mine avec un minerai non coché sera <strong>refusé</strong>.
    </p>

    @if(isset($minerais) && $minerais->isNotEmpty())
        @php
            $autorises = old(
                'minerais_autorises',
                isset($site) ? $site->mineraisAutorises->pluck('id')->toArray() : []
            );
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($minerais as $m)
                <label class="flex items-center gap-2 text-sm cursor-pointer p-2 rounded bg-white
                              border border-transparent has-[:checked]:border-yellow-400
                              has-[:checked]:bg-yellow-100 hover:bg-white/80">
                    <input type="checkbox"
                           name="minerais_autorises[]"
                           value="{{ $m->id }}"
                           @checked(in_array($m->id, $autorises))
                           class="rounded border-gray-300 text-yellow-600">
                    <span class="font-medium">{{ $m->nom }}</span>
                    <span class="text-gray-400 text-xs">({{ $m->unite }})</span>
                </label>
            @endforeach
        </div>
    @else
        <p class="text-xs text-gray-400 italic">Aucun minerai actif disponible.</p>
    @endif
</div>

<div class="flex items-center gap-2">
    <input type="hidden" name="actif" value="0">
    <input type="checkbox" id="actif" name="actif" value="1"
        @checked(old('actif', $site->actif ?? true))>
    <label for="actif" class="text-sm text-gray-700">Site actif</label>
</div>

<script>
    function toggleMineraisSection() {
        const type    = document.getElementById('select-type').value;
        const section = document.getElementById('section-minerais');
        if (type === 'mine') {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', toggleMineraisSection);
</script>
