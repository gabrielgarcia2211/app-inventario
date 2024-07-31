<?php

namespace App\Services\ProductInput;

use App\Models\ProductInput\ProductInput;
use App\Repositories\ProductInput\ProductInputRepositoryInterface;

class ProductInputService
{
    protected $productRepository;

    public function __construct(ProductInputRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductInputsQuery()
    {
        return ProductInput::query()
            ->leftJoin('products', 'products.id', '=', 'product_inputs.product_id')
            ->leftJoin('enum_options', 'enum_options.id', '=', 'product_inputs.client_id')
            ->groupBy(
                'product_inputs.id',
                'enum_options.id',
                'enum_options.name',
                'products.id',
                'products.name',
                'product_inputs.created_at',
            )
            ->orderBy('enum_options.name', 'asc')
            ->distinct();
    }
}
