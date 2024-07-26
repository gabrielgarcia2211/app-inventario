<?php

namespace App\Services\Product;

use App\Services\FileService;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ProductSize\ProductSize;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    protected $productRepository;
    protected $fileService;

    public function __construct(ProductRepositoryInterface $productRepository, FileService $fileService)
    {
        $this->productRepository = $productRepository;
        $this->fileService = $fileService;
    }

    public function getProductsQuery()
    {
        return Product::query()
            ->leftJoin('product_sizes', 'product_sizes.product_id', '=', 'products.id')
            ->groupBy(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.category',
                'products.is_total',
                'products.photo',
            )
            ->distinct();
    }

    public function storeProduct(array $data)
    {
        DB::beginTransaction();
        try {
            $sizes = $this->processSizes($data);
            $path = $data['photo'];
            unset($data['photo']);
            $product = $this->productRepository->create($data);
            $this->createProductSizes($product->id, $sizes);
            $product->photo = $this->fileService->saveFile($path, 1, 'photo');
            $product->save();

            DB::commit();
            return $product;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateProduct(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $sizes = $this->processSizes($data);
            $pattern = '/\bhttps?:\/\/\S+\b/';
            $path = $data['photo'];
            unset($data['photo']);

            $currentProduct = Product::find($id);
            if (!preg_match($pattern, $path)) {
                $this->fileService->deleteFile(cleanStorageUrl($currentProduct->photo));
                $currentProduct->photo = $this->fileService->saveFile($path, 1, 'photo');
                $currentProduct->save();
            }

            $product = $this->productRepository->update($id, $data);
            ProductSize::where('product_id', $product->id)->delete();
            $this->createProductSizes($product->id, $sizes);

            DB::commit();
            return $product;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteProduct($id)
    {
        try {
            $currentProduct = Product::find($id);
            if ($this->fileService->deleteFile(cleanStorageUrl($currentProduct->photo))) {
                $this->productRepository->delete($id);
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function showProduct($id)
    {
        try {
            return $this->productRepository->find($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    private function processSizes(array $data)
    {
        if ($data['is_total'] == 'SIZE' && isset($data['sizes']) && is_array($data['sizes'])) {
            return $data['sizes'];
        }
        return [
            [
                'size' => 'ALL',
                'quantity' => $data['quantity'],
            ]
        ];
    }

    private function createProductSizes($productId, array $sizes)
    {
        foreach ($sizes as $size) {
            ProductSize::create([
                'product_id' => $productId,
                'size' => $size['size'],
                'quantity' => $size['quantity'] ?? 0,
            ]);
        }
    }
}