<?php

namespace App\Models\Product;

use App\Enums\categoryProductEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'is_total',
        'photo',
    ];

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
