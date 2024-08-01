<?php

namespace App\Models\ProductInputDetail;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductInput\ProductInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductInputDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_input_id',
        'size',
        'quantity',
    ];

    public function input()
    {
        return $this->belongsTo(ProductInput::class);
    }
}