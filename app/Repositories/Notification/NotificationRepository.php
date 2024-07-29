<?php

namespace App\Repositories\Notification;

use App\Models\Notification\Notification;
use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected $model;

    public function __construct(Notification $product)
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
