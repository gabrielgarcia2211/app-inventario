<?php

namespace App\Models\ProductNotification;

use Illuminate\Database\Eloquent\Model;

class ProductNotification extends Model
{
    protected $fillable = ['product_id', 'type'];
}
