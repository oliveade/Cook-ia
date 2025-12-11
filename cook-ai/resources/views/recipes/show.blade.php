<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $recipe->title }}
            </h2>

            <a href="{{ route('recipes.index') }}" class="text-sm text-indigo-600 hover:underline">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <div class="bg-white p-6 rounded shadow">
                <p class="text-sm text-gray-700">{{ $recipe->description }}</p>
                <p class="text-gray-500 mt-2">
                    Type : {{ $recipe->type ?? 'Non précisé' }} • {{ $recipe->servings }} personne(s)
                </p>
            </div>


            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-2">Instructions</h3>
                <div class="prose">
                    {!! nl2br(e($recipe->instructions)) !!}
                </div>
            </div>


            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Analyse nutritionnelle (par personne)</h3>

                @if($nutrition)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Calories</p>
                        <p class="text-lg font-semibold">
                            {{ $nutrition->calories ?? '—' }} kcal
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Protéines</p>
                        <p class="text-lg font-semibold">
                            {{ $nutrition->proteins ?? '—' }} g
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Glucides</p>
                        <p class="text-lg font-semibold">
                            {{ $nutrition->carbs ?? '—' }} g
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Lipides</p>
                        <p class="text-lg font-semibold">
                            {{ $nutrition->fats ?? '—' }} g
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Vitamines</h4>
                        @if(is_array($nutrition->vitamins) && !empty($nutrition->vitamins))
                        <ul class="text-sm text-gray-700 space-y-1">
                            @foreach($nutrition->vitamins as $name => $value)
                            <li>{{ $name }} : {{ $value }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-gray-500">Non renseigné.</p>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Minéraux</h4>
                        @if(is_array($nutrition->minerals) && !empty($nutrition->minerals))
                        <ul class="text-sm text-gray-700 space-y-1">
                            @foreach($nutrition->minerals as $name => $value)
                            <li>{{ $name }} : {{ $value }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-sm text-gray-500">Non renseigné.</p>
                        @endif
                    </div>
                </div>
                @else
                <p class="text-gray-500 text-sm">
                    L’analyse nutritionnelle n’a pas encore été calculée pour cette recette.
                </p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>