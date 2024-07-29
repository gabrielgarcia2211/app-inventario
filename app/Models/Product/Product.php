<?php

namespace App\Models\Product;

use App\Models\ProductSize\ProductSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Enums\Product\categoryProductEnum;
use App\Models\ProductOutflow\ProductOutflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'section',
        'is_total',
        'photo',
    ];

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function ProductOutflows()
    {
        return $this->hasMany(ProductOutflow::class);
    }

    protected $appends = ['adjusted_price'];

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_product')->url($value);
        }
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
}
