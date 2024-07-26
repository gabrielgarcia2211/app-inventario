<?php

namespace App\Http\Controllers\ProductOutflow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProductOutflow\ProductOutflowService;
use App\Http\Controllers\ResponseController as Response;

class ProductOutflowController extends Controller
{
    protected $productOutflowService;

    public function __construct(ProductOutflowService $productOutflowService)
    {
        $this->productOutflowService = $productOutflowService;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->productOutflowService->getProductOutflowsQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'product_outflows.*',
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
