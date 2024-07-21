<?php

namespace App\Models\ProductSize;

use App\Enums\Size;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'size', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
