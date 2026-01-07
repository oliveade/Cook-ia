<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listes de courses
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Création d'une nouvelle liste --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-1">
                    Créer une liste de courses
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Sélectionne les recettes à inclure
                    <span class="italic">(exemple : 3 plats et 2 desserts pour une semaine)</span>.
                </p>

                <form method="POST" action="{{ route('shopping-lists.store') }}" class="space-y-6">
                    @csrf

                    {{-- Titre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Titre de la liste
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Ex : Courses semaine prochaine"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        @error('title')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sélection des recettes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Recettes disponibles
                        </label>

                        @error('recipes')
                            <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                        @enderror

                        @if($recipes->isEmpty())
                            <p class="text-sm text-gray-500">
                                Tu n’as encore créé aucune recette.
                            </p>
                        @else
                            <div class="border rounded-lg max-h-80 overflow-y-auto divide-y">
                                @foreach($recipes as $recipe)
                                    <label class="flex items-start gap-4 p-4 hover:bg-gray-50">
                                        <input
                                            type="checkbox"
                                            name="recipes[]"
                                            value="{{ $recipe->id }}"
                                            class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            @checked(collect(old('recipes', []))->contains($recipe->id))
                                        />

                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $recipe->title }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $recipe->type ?? 'Type non précisé' }}
                                                • {{ $recipe->servings }} pers.
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Bouton --}}
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition"
                            @disabled($recipes->isEmpty())
                        >
                            Générer la liste de courses
                        </button>
                    </div>
                </form>
            </div>

            {{-- Listes déjà créées --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4">
                    Mes listes de courses
                </h3>

                @if($lists->isEmpty())
                    <p class="text-sm text-gray-500">
                        Aucune liste de courses pour le moment.
                    </p>
                @else
                    <ul class="divide-y">
                        @foreach($lists as $list)
                            <li class="py-3 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $list->title }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Créée le {{ $list->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <a
                                    href="{{ route('shopping-lists.show', $list) }}"
                                    class="text-indigo-600 text-sm font-medium hover:underline"
                                >
                                    Voir →
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
