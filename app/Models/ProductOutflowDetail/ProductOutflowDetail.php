<?php

namespace App\Models\ProductOutflowDetail;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductOutflow\ProductOutflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOutflowDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_outflow_id',
        'size',
        'quantity',
    ];

    public function outflow()
    {
        return $this->belongsTo(ProductOutflow::class);
    }
}