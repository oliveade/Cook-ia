<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Affiche la page de profil (compte + réglages IA)
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // Si l'utilisateur n'a pas encore de profil, on en crée un vide
        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'goal' => null,
                'intolerances' => [],
                'calories_target' => null,
            ]
        );

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Met à jour les infos du compte + le profil IA
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],

            'goal'  => ['nullable', 'string', 'in:prise_masse,perte_poids,energie'],
            'intolerances'   => ['nullable', 'array'],
            'intolerances.*' => ['string'],
            'calories_target' => ['nullable', 'integer', 'min:0'],
        ]);

    
        if ($validated['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        $profile = Profile::firstOrCreate(
            ['user_id' => $user->id]
        );

        $profile->goal = $validated['goal'] ?? null;
        $profile->intolerances = $validated['intolerances'] ?? [];
        $profile->calories_target = $validated['calories_target'] ?? null;
        $profile->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprime le compte utilisateur (standard Breeze)
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
