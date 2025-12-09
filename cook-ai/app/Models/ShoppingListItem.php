<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model
{
    protected $fillable = ['shopping_list_id', 'ingredient_id', 'quantity', 'unit'];

    public function list()
    {
        return $this->belongsTo(ShoppingList::class, 'shopping_list_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
