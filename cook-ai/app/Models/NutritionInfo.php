<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionInfo extends Model
{
    protected $fillable = [
        'recipe_id',
        'calories',
        'proteins',
        'carbs',
        'fats',
        'vitamins',
        'minerals'
    ];

    protected $casts = [
        'vitamins' => 'array',
        'minerals' => 'array',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
