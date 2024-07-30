<?php

namespace App\Models\Product;

use App\Models\ProductSize\ProductSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductOutflow\ProductOutflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
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

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_product')->url($value);
        }
    }
}
