<?php

namespace App\Repositories\ProductOutflow;

use App\Models\ProductOutflow\ProductOutflow;
use App\Repositories\ProductOutflow\ProductOutflowRepositoryInterface;

class ProductOutflowRepository implements ProductOutflowRepositoryInterface
{
    protected $model;

    public function __construct(ProductOutflow $productOutflow)
    {
        $this->model = $productOutflow;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $product = $this->model->findOrFail($id);
        $product->update($attributes);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->model->findOrFail($id);
        $product->delete();
        return $product;
    }
}
