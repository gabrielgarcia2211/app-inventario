<?php

namespace App\Http\Controllers\ProductSize;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductSize\ProductSizeService;
use App\Http\Requests\ProductSize\EnterProductSizeRequest;
use App\Http\Requests\ProductSize\ExtractProductSizeRequest;

class ProductSizeController extends Controller
{
    protected $productSizeService;

    public function __construct(ProductSizeService $productSizeService)
    {
        $this->productSizeService = $productSizeService;
    }

    public function enterProduct(EnterProductSizeRequest $request, $id)
    {
        return $this->productSizeService->enterProduct($request->all(), $id);
    }

    public function extractProduct(ExtractProductSizeRequest $request, $id)
    {
        return $this->productSizeService->extractProductSale($request->all(), $id);
    }
}