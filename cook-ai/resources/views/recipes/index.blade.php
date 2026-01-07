<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes recettes
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-xl shadow mb-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('recipes.index') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Recherche
                </label>
                <input
                    type="text"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="Nom ou ingrédient (ex : poulet, chocolat...)"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Type de plat
                </label>
                <select
                    name="type"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Tous</option>
                    <option value="entrée" @selected(($filters['type'] ?? '') === 'entrée')>Entrée</option>
                    <option value="plat" @selected(($filters['type'] ?? '') === 'plat')>Plat</option>
                    <option value="dessert" @selected(($filters['type'] ?? '') === 'dessert')>Dessert</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="submit"
                    class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition"
                >
                    Rechercher
                </button>

                <a
                    href="{{ route('recipes.index') }}"
                    class="w-full px-4 py-2 rounded-lg border text-center text-gray-700 hover:bg-gray-50"
                >
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des recettes --}}
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Voici les recettes que tu as générées.
                </p>

                <a href="{{ route('recipes.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Nouvelle recette
                </a>
            </div>

            @if($recipes->isEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-gray-500">
                        @if(request()->filled('q') || request()->filled('type'))
                            Aucune recette ne correspond à ta recherche.
                        @else
                            Tu n’as encore aucune recette. Commence par en générer une 😋
                        @endif
                    </p>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @foreach($recipes as $recipe)
                            <li class="p-6 flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="hover:underline">
                                            {{ $recipe->title }}
                                        </a>
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $recipe->type ?? 'Type non précisé' }} ·
                                        pour {{ $recipe->servings }} personne(s)
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        Créée le {{ $recipe->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <a href="{{ route('recipes.show', $recipe) }}"
                                   class="text-indigo-600 text-sm font-medium hover:underline">
                                    Voir le détail →
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
