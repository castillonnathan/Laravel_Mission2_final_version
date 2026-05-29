<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Modifier le site : {{ $site->nom }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <form method="POST" action="{{ route('sites.update', $site) }}"
                  class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @csrf @method('PUT')
                @include('sites._form', ['site' => $site])
                <div class="flex gap-2 justify-end pt-2">
                    <a href="{{ route('sites.index') }}"
                       class="px-4 py-2 text-gray-600 hover:text-gray-800">Annuler</a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
