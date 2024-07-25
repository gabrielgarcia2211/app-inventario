<?php

namespace App\Http\Controllers\ProductSize;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController as Response;
use App\Models\ProductSize\ProductSize;

class ProductSizeController extends Controller
{
    public function __construct()
    {
    }

    public function extractProduct(Request $request)
    {
        $data = $request->all();
        foreach ($data as $key => $value) {
            $size = ProductSize::find($value['id']);
            $size->quantity = $size->quantity - $value['quantity'];
            $size->save();
        }
        return true;
    }
}
