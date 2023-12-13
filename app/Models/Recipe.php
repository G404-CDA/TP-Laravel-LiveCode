<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ingredients',
        'preparationTime',
        'cookingTime',
        'serves',
    ];

    protected $casts = [
        'ingredients' => 'json',
        'preparationTime' => 'string',
        'cookingTime' => 'string',
        'serves' => 'integer'
    ];
}


