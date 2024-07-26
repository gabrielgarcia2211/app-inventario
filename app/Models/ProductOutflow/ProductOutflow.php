<?php

namespace App\Models\ProductOutflow;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductOutflowDetail\ProductOutflowDetail;

class ProductOutflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'client_name',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(ProductOutflowDetail::class);
    }
}
