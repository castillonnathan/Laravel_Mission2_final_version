<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des rôles
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Messages flash --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Créer un rôle --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Créer un nouveau rôle</h3>
                <form method="POST" action="{{ route('admin.roles.store') }}" class="flex gap-4 items-end flex-wrap">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom interne</label>
                        <input type="text" name="name" placeholder="technicien"
                               value="{{ old('name') }}"
                               class="mt-1 border-gray-300 rounded-md shadow-sm" required>
                        @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Label affiché</label>
                        <input type="text" name="label" placeholder="Technicien"
                               value="{{ old('label') }}"
                               class="mt-1 border-gray-300 rounded-md shadow-sm" required>
                        @error('label') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Créer
                    </button>
                </form>
            </div>

            {{-- Liste des rôles --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Rôles existants</h3>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Nom interne</th>
                        <th class="px-4 py-3">Label</th>
                        <th class="px-4 py-3">Utilisateurs</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($roles as $role)
                        <tr>
                            <td class="px-4 py-3 font-mono">{{ $role->name }}</td>
                            <td class="px-4 py-3">{{ $role->label }}</td>
                            <td class="px-4 py-3">{{ $role->users_count }}</td>
                            <td class="px-4 py-3">
                                @if($role->name !== 'admin')
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                          onsubmit="return confirm('Supprimer ce rôle ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            Supprimer
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">Protégé</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Gérer les rôles par utilisateur --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Rôles par utilisateur</h3>
                <div class="space-y-4">
                    @foreach($users as $user)
                        @php
                            // IDs des rôles déjà attribués (uniquement pour marquer "déjà attribué" dans le select)
                            $assignedRoleIds = $user->roles->pluck('id')->all();
                        @endphp

                        <div class="border rounded-lg p-4">
                            <div class="flex items-start justify-between flex-wrap gap-4">

                                {{-- Infos utilisateur + badges --}}
                                <div class="flex-1 min-w-[250px]">
                                    <p class="font-medium">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>

                                    <div class="flex gap-2 mt-2 flex-wrap">
                                        @forelse($user->roles as $role)
                                            <form method="POST"
                                                  action="{{ route('admin.users.roles.remove', [$user, $role]) }}"
                                                  onsubmit="return confirm('Retirer le rôle « {{ $role->label }} » à {{ $user->name }} ?')"
                                                  class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                                                @csrf @method('DELETE')
                                                <span>{{ $role->label }}</span>
                                                @if(!($role->name === 'admin' && $user->id === auth()->id()))
                                                    <button type="submit"
                                                            class="hover:text-red-600 font-bold ml-1 leading-none cursor-pointer"
                                                            title="Retirer ce rôle">
                                                        ×
                                                    </button>
                                                @endif
                                            </form>
                                        @empty
                                            <span class="text-gray-400 text-xs">Aucun rôle</span>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Assigner un rôle (TOUS les rôles sont affichés, même ceux déjà attribués) --}}
                                <form method="POST"
                                      action="{{ route('admin.users.roles.assign', $user) }}"
                                      class="flex gap-2 items-center">
                                    @csrf
                                    <select name="role"
                                            class="border-gray-300 rounded-md shadow-sm text-sm"
                                            required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">
                                                {{ $role->label }}@if(in_array($role->id, $assignedRoleIds)) (déjà attribué)@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
                                            class="bg-green-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-green-700">
                                        Ajouter
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
