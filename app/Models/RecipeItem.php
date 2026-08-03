<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecipeItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'material_name',
        'unit',
        'quantity',
        'base_yield_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
