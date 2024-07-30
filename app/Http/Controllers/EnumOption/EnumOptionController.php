<?php

namespace App\Http\Controllers\EnumOption;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\EnumOption\EnumOptionService;
use App\Http\Controllers\ResponseController as Response;

class EnumOptionController extends Controller
{
    protected $enumService;

    public function __construct(EnumOptionService $enumService)
    {
        $this->enumService = $enumService;
    }

    public function listChildrens($name)
    {
        try {
            $enums = $this->enumService->listChildrens($name);
            return Response::sendResponse($enums, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
