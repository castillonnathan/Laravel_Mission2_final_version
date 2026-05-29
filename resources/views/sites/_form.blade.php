{{-- Champs partagés entre create et edit --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom *</label>
        <input type="text" name="nom" required
               value="{{ old('nom', $site->nom ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Type *</label>
        <select name="type" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach(['mine' => 'Mine', 'depot' => 'Dépôt', 'client_site' => 'Site client', 'autre' => 'Autre'] as $val => $lbl)
                <option value="{{ $val }}" @selected(old('type', $site->type ?? 'depot') === $val)>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
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

<div class="flex items-center gap-2">
    <input type="hidden" name="actif" value="0">
    <input type="checkbox" id="actif" name="actif" value="1"
        @checked(old('actif', $site->actif ?? true))>
    <label for="actif" class="text-sm text-gray-700">Site actif</label>
</div>
