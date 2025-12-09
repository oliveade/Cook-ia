<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }
    public function shoppingListItems()
    {
        return $this->hasMany(ShoppingListItem::class);
    }
}
