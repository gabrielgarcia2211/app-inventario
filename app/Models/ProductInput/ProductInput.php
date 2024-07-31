<?php

namespace App\Models\ProductInput;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'seamstre_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
