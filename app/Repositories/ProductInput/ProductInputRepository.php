<?php

namespace App\Repositories\ProductInput;

use App\Models\ProductInput\ProductInput;
use App\Repositories\ProductInput\ProductInputRepositoryInterface;

class ProductInputRepository implements ProductInputRepositoryInterface
{
    protected $model;

    public function __construct(ProductInput $productInput)
    {
        $this->model = $productInput;
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
