<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes recettes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Voici les recettes que tu as générées.
                </p>
                <a href="{{ route('recipes.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-indigo-700">
                    Nouvelle recette
                </a>
            </div>

            @if($recipes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-500">
                        Tu n’as encore aucune recette. Commence par en générer une 😋
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
