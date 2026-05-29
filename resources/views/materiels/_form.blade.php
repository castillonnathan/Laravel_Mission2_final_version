@php $isEdit = isset($materiel); @endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    <div class="md:col-span-2">
        <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom *</label>
        <input id="nom" name="nom" type="text" required autofocus
               value="{{ old('nom', $isEdit ? $materiel->nom : '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm" />
        @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="quantite_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité en stock *</label>
        <input id="quantite_stock" name="quantite_stock" type="number" min="0" required
               value="{{ old('quantite_stock', $isEdit ? $materiel->quantite_stock : 0) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm" />
        @error('quantite_stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="seuil_alerte" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seuil d'alerte *</label>
        <input id="seuil_alerte" name="seuil_alerte" type="number" min="0" required
               value="{{ old('seuil_alerte', $isEdit ? $materiel->seuil_alerte : 0) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm" />
        <p class="text-gray-400 text-xs mt-1">Une alerte s'affiche quand le stock ≤ ce seuil.</p>
        @error('seuil_alerte') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">{{ old('description', $isEdit ? $materiel->description : '') }}</textarea>
        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

</div>
