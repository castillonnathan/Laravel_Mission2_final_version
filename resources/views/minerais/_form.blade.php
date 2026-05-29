<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Code *</label>
        <input type="text" name="code" required maxlength="20"
               value="{{ old('code', $minerai->code ?? '') }}"
               placeholder="FE, CU, AU…"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm font-mono">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom *</label>
        <input type="text" name="nom" required
               value="{{ old('nom', $minerai->nom ?? '') }}"
               placeholder="Fer, Cuivre, Or…"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Unité *</label>
    <select name="unite" required
            class="mt-1 block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm">
        @foreach(['tonne', 'kg', 'm3'] as $u)
            <option value="{{ $u }}" @selected(old('unite', $minerai->unite ?? 'tonne') === $u)>
                {{ $u }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" rows="3"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $minerai->description ?? '') }}</textarea>
</div>

<div class="flex items-center gap-2">
    <input type="hidden" name="actif" value="0">
    <input type="checkbox" id="actif" name="actif" value="1"
        @checked(old('actif', $minerai->actif ?? true))>
    <label for="actif" class="text-sm text-gray-700">Minerai actif</label>
</div>
