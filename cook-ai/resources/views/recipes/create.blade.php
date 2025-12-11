<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Générer une recette
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">

                <form method="POST" action="{{ route('recipes.generate') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Ingrédients</label>
                        <textarea name="ingredients" required class="w-full border rounded p-2" rows="3"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nombre de personnes</label>
                        <input type="number" name="servings" value="2" min="1" required class="border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Type de plat</label>
                        <select name="type" class="border rounded p-2">
                            <option value="">Laisser l’IA choisir</option>
                            <option value="entrée">Entrée</option>
                            <option value="plat">Plat</option>
                            <option value="dessert">Dessert</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-black rounded hover:bg-indigo-700 text-sm">
                        Générer avec l’IA
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
