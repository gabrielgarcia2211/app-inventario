<?php

namespace App\Repositories\EnumOption;

use App\Models\Configuration\EnumOption;
use App\Repositories\EnumOption\EnumOptionRepositoryInterface;

class EnumOptionRepository implements EnumOptionRepositoryInterface
{
    protected $model;

    public function __construct(EnumOption $product)
    {
        $this->model = $product;
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
}
