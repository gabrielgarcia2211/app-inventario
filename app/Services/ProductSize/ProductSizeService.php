<?php

namespace App\Services\ProductSize;

use Illuminate\Support\Facades\DB;
use App\Models\ProductSize\ProductSize;

class ProductSizeService
{
    public function extractProductAndLogSale(array $data)
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $value) {
                $size = ProductSize::find($value['id']);
                $size->quantity = $size->quantity - $value['quantity'];
                $size->save();
            }
        });
    }
}