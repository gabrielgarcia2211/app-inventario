<?php

namespace App\Http\Controllers\Product;

use App\Enums\Size;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        try {
            $product = $this->productRepository->create($request->all());
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

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

    public function getProductSize()
    {
        $sizes = Size::getValues();
        $sizesWithQuantity = array_map(fn ($size) => ['size' => $size, 'quantity' => 0], $sizes);
        return response()->json($sizesWithQuantity);
    }
}
