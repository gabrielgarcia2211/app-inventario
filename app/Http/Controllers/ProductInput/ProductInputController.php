<?php

namespace App\Http\Controllers\ProductInput;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProductInput\ProductInputService;
use App\Http\Controllers\ResponseController as Response;

class ProductInputController extends Controller
{
    protected $productInputService;

    public function __construct(ProductInputService $productInputService)
    {
        $this->productInputService = $productInputService;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->productInputService->getProductInputsQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'enum_options.name as seamstress',
                    'products.name as name',
                    'products.id as product_id',
                    DB::raw("DATE_FORMAT(product_inputs.created_at, '%d-%m-%Y %H:%i:%s') as date_entry"),
                    DB::raw('GROUP_CONCAT(CONCAT(product_input_details.size, ":", product_input_details.quantity) ORDER BY product_input_details.size ASC SEPARATOR ";") as outflows'),
                    DB::raw('SUM(product_input_details.quantity) as total_quantity')
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
