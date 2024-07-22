<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Enums\sizeProductEnum;
use App\Models\Product\Product;
use App\Enums\categoryProductEnum;
use Illuminate\Support\Facades\DB;
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
    protected $fileService;

    public function __construct(ProductRepositoryInterface $productRepository, FileService $fileService)
    {
        $this->productRepository = $productRepository;
        $this->fileService = $fileService;
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
                    'products.name',
                    'products.description',
                    'products.price',
                    'products.category',
                    'products.is_total',
                    'products.photo',
                    DB::raw('GROUP_CONCAT(CONCAT(product_sizes.size, ":", product_sizes.quantity) SEPARATOR ";") as sizes')
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
            if ($data['is_total'] == 'SIZE' && isset($data['sizes']) && is_array($data['sizes'])) {
                $sizes = $data['sizes'];
            } else {
                $sizes = [
                    [
                        'size' => 'TOTAL',
                        'quantity' => $data['quantity'],
                    ]
                ];
            }
            $path = $data['photo'];
            unset($data['photo']);
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
            if ($product) {
                $product->photo = $this->fileService->saveFile($path, 1, 'photo');
                $product->save();
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
            ->leftJoin('product_sizes', 'product_sizes.product_id', '=', 'products.id')
            ->groupBy(
                'products.name',
                'products.description',
                'products.price',
                'products.category',
                'products.is_total',
                'products.photo',
            )
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
