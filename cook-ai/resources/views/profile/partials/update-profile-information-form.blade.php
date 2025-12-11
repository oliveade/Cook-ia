<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informations du compte & préférences
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Mets à jour ton nom, ton email et tes préférences pour la génération de recettes.
        </p>
    </header>

    @if (session('status') === 'profile-updated')
        <p class="mt-2 text-sm text-green-600">
            Profil mis à jour avec succès 
        </p>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Nom --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Nom
            </label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="mt-1 block w-full border rounded-md p-2">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="mt-1 block w-full border rounded-md p-2">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <hr class="my-4">

        {{-- Objectif --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Objectif principal
            </label>
            <select name="goal" class="border rounded-md p-2">
                <option value="">Aucun objectif particulier</option>
                <option value="prise_masse" @selected(old('goal', $profile->goal ?? null) === 'prise_masse')>
                    Prise de masse
                </option>
                <option value="perte_poids" @selected(old('goal', $profile->goal ?? null) === 'perte_poids')>
                    Perte de poids
                </option>
                <option value="energie" @selected(old('goal', $profile->goal ?? null) === 'energie')>
                    Gain d’énergie
                </option>
            </select>
            @error('goal')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Intolérances --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Intolérances alimentaires
            </label>

            @php
                $selectedIntolerances = old('intolerances', $profile->intolerances ?? []);
            @endphp

            <div class="space-y-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="intolerances[]" value="gluten"
                           @checked(in_array('gluten', $selectedIntolerances))>
                    <span class="ml-2 text-sm">Gluten</span>
                </label>
                <br>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="intolerances[]" value="lactose"
                           @checked(in_array('lactose', $selectedIntolerances))>
                    <span class="ml-2 text-sm">Lactose</span>
                </label>
                <br>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="intolerances[]" value="nuts"
                           @checked(in_array('nuts', $selectedIntolerances))>
                    <span class="ml-2 text-sm">Fruits à coque</span>
                </label>
            </div>

            @error('intolerances')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Objectif calorique --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Objectif calorique quotidien (kcal)
            </label>
            <input type="number" name="calories_target"
                   value="{{ old('calories_target', $profile->calories_target ?? null) }}"
                   class="mt-1 block w-40 border rounded-md p-2">
            @error('calories_target')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                Enregistrer
            </button>
        </div>
    </form>
</section>
