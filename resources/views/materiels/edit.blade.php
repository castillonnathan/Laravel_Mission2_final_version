<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Modifier — {{ $materiel->nom }}
        </h2>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form method="POST" action="{{ route('materiels.update', $materiel) }}">
                    @csrf @method('PUT')
                    @include('materiels._form')
                    <div class="mt-6 flex gap-3 items-center">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            Mettre à jour
                        </button>
                        <a href="{{ route('materiels.index') }}" class="text-sm text-gray-500 hover:underline">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
