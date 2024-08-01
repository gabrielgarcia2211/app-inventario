<?php

namespace App\Repositories\EnumOption;

interface EnumOptionRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}
