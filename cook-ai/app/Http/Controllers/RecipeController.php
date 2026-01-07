<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\NutritionInfo;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Recipe::where('user_id', $user->id)
            ->with('ingredients');

        if ($request->filled('q')) {
            $search = $request->q;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('ingredients', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $recipes = $query
            ->latest()
            ->get();

        return view('recipes.index', [
            'recipes' => $recipes,
            'filters' => $request->only(['q', 'type']),
        ]);
    }


    public function create()
    {
        return view('recipes.create');
    }

    public function show(Recipe $recipe)
    {
        abort_unless($recipe->user_id === auth()->id(), 403);

        $recipe->load('ingredients', 'nutrition');

        return view('recipes.show', [
            'recipe'      => $recipe,
            'ingredients' => $recipe->ingredients,
            'nutrition'   => $recipe->nutrition,
        ]);
    }

    public function generate(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $data = $request->validate([
            'ingredients' => 'required|string',
            'servings'    => 'required|integer|min:1',
            'type'        => 'nullable|string',
        ]);

        $profilePart = '';
        if ($profile) {
            $profilePart = 'Prends en compte : '
                . 'objectif = ' . ($profile->goal ?? 'non précisé') . ', '
                . 'intolérances = ' . json_encode($profile->intolerances ?? []);
        }

        $userIngredients = $data['ingredients'];
        $servings        = $data['servings'];
        $type            = $data['type'] ?: 'plat';

        $prompt = <<<EOT
        Tu es un assistant de cuisine. Génère une recette en français au format STRICTEMENT JSON.

        Contraintes :
        - La recette doit utiliser principalement ces ingrédients : {$userIngredients}
        - La recette doit être pour {$servings} personne(s)
        - Type de plat : {$type}
        - {$profilePart}

        Réponds UNIQUEMENT avec un JSON de cette forme :

        {
        "title": "Titre de la recette",
        "description": "Description courte",
        "type": "entrée | plat | dessert",
        "servings": 2,
        "ingredients": [
            { "name": "nom de l'ingrédient", "quantity": 200, "unit": "g" },
            { "name": "autre ingrédient", "quantity": 1, "unit": "pièce" }
        ],
        "instructions": "Texte avec les étapes de la recette, sous forme de listes."
        }
        EOT;

        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4.1-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un assistant de cuisine qui répond uniquement en JSON valide.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            return back()->withErrors([
                'openai' => 'Erreur lors de la génération de la recette. Réessaie plus tard.',
            ])->withInput();
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            return back()->withErrors([
                'openai' => 'Réponse invalide de l’IA.',
            ])->withInput();
        }

        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors([
                'openai' => 'Impossible de parser la réponse JSON de l’IA.',
            ])->withInput();
        }

        $recipe = Recipe::create([
            'user_id'     => $user->id,
            'title'       => $json['title'] ?? 'Recette sans titre',
            'description' => $json['description'] ?? null,
            'type'        => $json['type'] ?? $type,
            'servings'    => $json['servings'] ?? $servings,
            'instructions' => $json['instructions'] ?? '',
        ]);

        if (!empty($json['ingredients']) && is_array($json['ingredients'])) {
            foreach ($json['ingredients'] as $item) {
                $name     = $item['name'] ?? null;
                $quantity = $item['quantity'] ?? null;
                $unit     = $item['unit'] ?? null;

                if (!$name) {
                    continue;
                }

                $ingredient = Ingredient::firstOrCreate([
                    'name' => mb_strtolower($name),
                ]);

                $recipe->ingredients()->attach($ingredient->id, [
                    'quantity' => $quantity,
                    'unit'     => $unit,
                ]);
            }
        }

        $recipe->load('ingredients');


        try {
            $ingredientsForAi = [];

            foreach ($recipe->ingredients as $ingredient) {
                $ingredientsForAi[] = [
                    'name'     => $ingredient->name,
                    'quantity' => $ingredient->pivot->quantity,
                    'unit'     => $ingredient->pivot->unit,
                ];
            }

            $nutritionPrompt = <<<EOT
            Tu es un nutritionniste. À partir de la recette suivante, estime les valeurs nutritionnelles PAR PERSONNE.

            Recette : {$recipe->title}
            Nombre de personnes : {$recipe->servings}

            Ingrédients (quantité totale pour toute la recette) :
            EOT;

            foreach ($ingredientsForAi as $ing) {
                $nutritionPrompt .= "\n- {$ing['quantity']} {$ing['unit']} de {$ing['name']}";
            }

            $nutritionPrompt .= <<<EOT


            Réponds STRICTEMENT en JSON de la forme suivante (valeurs approximatives mais réalistes, par personne) :

            {
            "calories": 600,
            "proteins": 30,
            "carbs": 50,
            "fats": 20,
            "vitamins": {
                "A": "10%",
                "C": "25%",
                "D": "5%"
            },
            "minerals": {
                "calcium": "8%",
                "iron": "12%"
            }
            }
            EOT;

            $nutritionResponse = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4.1-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un nutritionniste qui répond uniquement en JSON valide.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $nutritionPrompt
                        ],
                    ],
                    'response_format' => ['type' => 'json_object'],
                ]);

            if (!$nutritionResponse->failed()) {
                $nutritionContent = $nutritionResponse->json()['choices'][0]['message']['content'] ?? null;

                if ($nutritionContent) {
                    $nutritionJson = json_decode($nutritionContent, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        NutritionInfo::create([
                            'recipe_id' => $recipe->id,
                            'calories'  => $nutritionJson['calories'] ?? null,
                            'proteins'  => $nutritionJson['proteins'] ?? null,
                            'carbs'     => $nutritionJson['carbs'] ?? null,
                            'fats'      => $nutritionJson['fats'] ?? null,
                            'vitamins'  => $nutritionJson['vitamins'] ?? [],
                            'minerals'  => $nutritionJson['minerals'] ?? [],
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            //
        }

        return redirect()->route('recipes.show', $recipe)
            ->with('status', 'Recette générée avec succès !');
    }
}
