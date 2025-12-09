<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }
    public function nutrition()
    {
        return $this->hasOne(NutritionInfo::class);
    }
}
