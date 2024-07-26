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
            ->leftJoin('products', 'products.id', '=', 'product_outflows.product_id')
            ->leftJoin('product_outflow_details', 'product_outflow_details.product_outflow_id', '=', 'product_outflows.id')
            ->groupBy(
                'product_outflows.id',
                'product_outflows.client_name',
                'products.name',
                'products.id',
                'products.created_at',
            )
            ->distinct();
    }
}
