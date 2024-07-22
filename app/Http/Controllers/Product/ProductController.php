<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Enums\sizeProductEnum;
use App\Models\Product\Product;
use App\Enums\categoryProductEnum;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ProductSize\ProductSize;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Controllers\ResponseController as Response;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->setQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'products.*'
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
            $data = $request->all();
            if (!$data['is_total'] && isset($data['sizes']) && is_array($data['sizes'])) {
                $sizes = $data['sizes'];
            } else {
                $sizes = [];
            }
            $product = $this->productRepository->create($data);
            if (!empty($sizes)) {
                foreach ($sizes as $size) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size' => $size['size'],
                        'quantity' => $size['quantity'],
                    ]);
                }
            }
            return Response::sendResponse($product, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productRepository->find($id);
            return Response::sendResponse($product, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = $this->productRepository->update($id, $request->all());
            return Response::sendResponse($product, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productRepository->delete($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    private function setQuery()
    {
        return Product::query()
            ->distinct();
    }

    public function getEnumProductSize()
    {
        $sizes = sizeProductEnum::getValues();
        $sizesWithQuantity = array_map(fn ($size) => ['size' => $size, 'quantity' => 0], $sizes);
        return response()->json($sizesWithQuantity);
    }

    public function getEnumProductCategory()
    {
        $category = categoryProductEnum::getValues();
        $categoryWithQuantity = array_map(fn ($category) => ['name' => $category], $category);
        return response()->json($categoryWithQuantity);
    }
}
