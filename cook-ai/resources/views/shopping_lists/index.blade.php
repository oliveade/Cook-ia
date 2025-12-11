<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listes de courses
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Formulaire de création d'une nouvelle liste --}}
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Créer une nouvelle liste de courses</h3>

                <form method="POST" action="{{ route('shopping-lists.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Titre de la liste
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="mt-1 block w-full border rounded-md p-2" required>
                        @error('title')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionne les recettes à inclure
                        </label>

                        @error('recipes')
                            <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                        @enderror

                        @if($recipes->isEmpty())
                            <p class="text-sm text-gray-500">
                                Tu n'as pas encore de recettes. Commence par en générer quelques-unes.
                            </p>
                        @else
                            <div class="max-h-64 overflow-y-auto border rounded-md p-3 space-y-2">
                                @foreach($recipes as $recipe)
                                    <label class="flex items-start space-x-2">
                                        <input type="checkbox"
                                               name="recipes[]"
                                               value="{{ $recipe->id }}"
                                               class="mt-1"
                                               @checked(collect(old('recipes', []))->contains($recipe->id))>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $recipe->title }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $recipe->type ?? 'Type non précisé' }} •
                                                {{ $recipe->servings }} pers.
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-black text-sm rounded hover:bg-indigo-700"
                                @disabled($recipes->isEmpty())>
                            Générer la liste de courses
                        </button>
                    </div>
                </form>
            </div>

            {{-- Liste des listes de courses existantes --}}
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Mes listes de courses</h3>

                @if($lists->isEmpty())
                    <p class="text-sm text-gray-500">
                        Tu n'as pas encore de liste de courses.
                    </p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($lists as $list)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $list->title }}</p>
                                    <p class="text-xs text-gray-500">
                                        Créée le {{ $list->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <a href="{{ route('shopping-lists.show', $list) }}"
                                   class="text-indigo-600 text-sm hover:underline">
                                    Voir la liste →
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
