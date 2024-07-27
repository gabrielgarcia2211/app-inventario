<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\Product\sizeProductEnum;
use App\Services\Product\ProductService;
use App\Enums\Product\sectionProductEnum;
use App\Enums\Product\categoryProductEnum;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Controllers\ResponseController as Response;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->productService->getProductsQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.price',
                    'products.category',
                    'products.section',
                    'products.is_total',
                    'products.photo',
                    DB::raw("DATE_FORMAT(products.created_at, '%d-%m-%Y %H:%i:%s') as fecha_ingreso"),
                    DB::raw('GROUP_CONCAT(CONCAT(product_sizes.size, ":", product_sizes.quantity) ORDER BY product_sizes.size ASC SEPARATOR ";") as sizes'),
                    DB::raw('SUM(product_sizes.quantity) as total_quantity')
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->storeProduct($request->all());
            return Response::sendResponse($product, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->showProduct($id);
            return Response::sendResponse($product, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = $this->productService->updateProduct($request->all(), $id);
            return Response::sendResponse($product, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function getEnumProductSize()
    {
        $sizes = sizeProductEnum::getValues();
        $sizesWithQuantity = array_map(fn ($size) => ['size' => $size, 'quantity' => 0], $sizes);
        return response()->json($sizesWithQuantity);
    }

    public function getEnumProductCategory()
    {
        $categories = categoryProductEnum::cases();
        $categoryWithNames = array_map(function ($category) {
            return [
                'name' => $category->value,
                'percentage' => $category->getPercentage()
            ];
        }, $categories);
        return response()->json($categoryWithNames);
    }

    public function getEnumProductSection()
    {
        $sections = sectionProductEnum::cases();
        $sectionWithNames = array_map(function ($section) {
            return [
                'name' => $section->value
            ];
        }, $sections);
        return response()->json($sectionWithNames);
    }
}
