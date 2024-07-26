<?php

namespace App\Http\Controllers\ProductSize;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductSize\ProductSizeService;
class ProductSizeController extends Controller
{
    protected $productSizeService;

    public function __construct(ProductSizeService $productSizeService)
    {
        $this->productSizeService = $productSizeService;
    }

    public function extractProduct(Request $request)
    {
        $data = $request->all()['currentQuantity'];
        $this->productSizeService->extractProductAndLogSale($data);
        return response()->json(['success' => true], 200);
    }
}