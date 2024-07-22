<?php

namespace App\Models\Product;

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

    public function getPhotoAttribute($value)
    {
        if ($value) {
            return Storage::disk('disk_product')->url($value);
        }
    }
}
