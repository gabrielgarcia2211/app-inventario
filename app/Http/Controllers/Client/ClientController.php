<?php

namespace App\Http\Controllers\Client;

use App\Enums\Client\nameClientEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController as Response;

class ClientController extends Controller
{

    public function __construct()
    {
    }

    public function getEnumClientName()
    {
        $name = nameClientEnum::getValues();
        $nameWithQuantity = array_map(fn ($name) => ['name' => $name], $name);
        return response()->json($nameWithQuantity);
    }
}
