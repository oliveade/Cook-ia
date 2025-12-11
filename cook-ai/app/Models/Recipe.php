<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'servings',
        'instructions',
    ];

    /**
     * Utilisateur qui a créé la recette
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ingrédients de la recette
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    /**
     * Analyse nutritionnelle de la recette (one-to-one)
     */
    public function nutrition()
    {
        return $this->hasOne(NutritionInfo::class);
    }
}
