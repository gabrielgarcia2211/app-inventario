<?php

namespace App\Services\ProductOutflow;

use App\Models\ProductOutflow\ProductOutflow;
use App\Repositories\ProductOutflow\ProductOutflowRepositoryInterface;

class ProductOutflowService
{
    protected $productRepository;

    public function __construct(ProductOutflowRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductOutflowsQuery()
    {
        return ProductOutflow::query()
            ->distinct();
    }
}
