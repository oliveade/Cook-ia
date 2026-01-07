<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingListController extends Controller
{
    /**
     * Affiche :
     * - les recettes de l'utilisateur (pour sélection)
     * - les listes de courses déjà créées
     */
    public function index()
    {
        $user = Auth::user();

        $recipes = $user->recipes()
            ->with('ingredients')
            ->latest()
            ->get();

        $lists = $user->shoppingLists()
            ->latest()
            ->get();

        return view('shopping_lists.index', compact('recipes', 'lists'));
    }

    /**
     * Crée une liste de courses à partir
     * d'une sélection libre de recettes
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'recipes'     => ['required', 'array', 'min:1'],
            'recipes.*'   => ['integer', 'exists:recipes,id'],
        ], [
            'recipes.required' => 'Sélectionne au moins une recette.',
        ]);
        
        $recipes = Recipe::where('user_id', $user->id)
            ->whereIn('id', $data['recipes'])
            ->with('ingredients')
            ->get();

        if ($recipes->isEmpty()) {
            return back()->withErrors([
                'recipes' => 'Aucune recette valide sélectionnée.',
            ])->withInput();
        }

        $shoppingList = ShoppingList::create([
            'user_id' => $user->id,
            'title'   => $data['title'],
        ]);

        /**
         * - on additionne les quantités
         * - on distingue par ingrédient + unité
         */
        $aggregated = [];

        foreach ($recipes as $recipe) {
            foreach ($recipe->ingredients as $ingredient) {
                $unit = $ingredient->pivot->unit ?: '';
                $key = $ingredient->id . '|' . $unit;

                if (!isset($aggregated[$key])) {
                    $aggregated[$key] = [
                        'ingredient_id' => $ingredient->id,
                        'quantity'      => 0,
                        'unit'          => $unit,
                    ];
                }

                $aggregated[$key]['quantity'] += (float) ($ingredient->pivot->quantity ?? 0);
            }
        }

        foreach ($aggregated as $itemData) {
            $shoppingList->items()->create($itemData);
        }

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('status', 'Liste de courses créée avec succès !');
    }

    /**
     * Affichage d'une liste de courses
     */
    public function show(ShoppingList $list)
    {
        abort_unless($list->user_id === Auth::id(), 403);

        $list->load('items.ingredient');

        return view('shopping_lists.show', [
            'list'  => $list,
            'items' => $list->items,
        ]);
    }
}
