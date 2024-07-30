<?php

namespace App\Http\Controllers\ProductOutflow;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    'enum_options.name as client',
                    'products.name as name',
                    'products.id as product_id',
                    DB::raw("DATE_FORMAT(product_outflows.created_at, '%d-%m-%Y %H:%i:%s') as fecha_salida"),
                    DB::raw('GROUP_CONCAT(CONCAT(product_outflow_details.size, ":", product_outflow_details.quantity) ORDER BY product_outflow_details.size ASC SEPARATOR ";") as outflows'),
                    DB::raw('SUM(product_outflow_details.quantity) as total_quantity')
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
