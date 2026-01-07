<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-semibold">
                        Bienvenue 
                    </p>
                    <p class="text-gray-600 mt-1">
                        Que souhaitez-vous faire aujourd’hui ?
                    </p>
                </div>
            </div>

            {{-- Actions rapides --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Recettes --}}
                <div class="bg-white shadow-sm rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Mes recettes
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Consultez, recherchez ou générez de nouvelles recettes personnalisées.
                        </p>
                    </div>

                    <a href="{{ route('recipes.index') }}"
                       class="mt-6 inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        Accéder aux recettes
                    </a>
                </div>

                {{-- Générer une recette --}}
                <div class="bg-white shadow-sm rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Générer une recette
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Créez une recette à partir de vos ingrédients et de votre profil.
                        </p>
                    </div>

                    <a href="{{ route('recipes.create') }}"
                       class="mt-6 inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-gray-800 font-semibold hover:bg-gray-50 transition">
                        Nouvelle recette
                    </a>
                </div>

                {{-- Listes de courses --}}
                <div class="bg-white shadow-sm rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Listes de courses
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Générez vos listes de courses à partir de vos recettes.
                        </p>
                    </div>

                    <a href="{{ route('shopping-lists.index') }}"
                       class="mt-6 inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-gray-800 font-semibold hover:bg-gray-50 transition">
                        Voir les listes
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
