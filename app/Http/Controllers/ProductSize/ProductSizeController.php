<?php

namespace App\Http\Controllers\ProductSize;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductSize\ProductSizeService;
use App\Http\Requests\ProductSize\StoreProductSizeRequest;

class ProductSizeController extends Controller
{
    protected $productSizeService;

    public function __construct(ProductSizeService $productSizeService)
    {
        $this->productSizeService = $productSizeService;
    }

    public function extractProduct(StoreProductSizeRequest $request, $id)
    {
        return $this->productSizeService->extractProductSale($request->all(), $id);
    }
}