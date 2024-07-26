<?php

namespace App\Models\ProductSize;

use App\Enums\Size;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Product\categoryProductEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'size', 'quantity'];
    protected $appends = ['initial_quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getAdjustedPriceAttribute()
    {
        if ($this->category) {
            $categoryEnum = categoryProductEnum::tryFrom($this->category);
            if ($categoryEnum) {
                $percentage = $categoryEnum->getPercentage();
                return $this->price * (1 + $percentage);
            }
        }
        return null;
    }

    public function getInitialQuantityAttribute()
    {
        return $this->attributes['quantity'];
    }

}
