<?php

namespace App\Repositories\Notification;

interface NotificationRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
}
