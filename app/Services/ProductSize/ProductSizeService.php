<?php

namespace App\Services\ProductSize;

use Illuminate\Support\Facades\DB;
use App\Models\ProductSize\ProductSize;
use App\Models\ProductInput\ProductInput;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductOutflow\ProductOutflow;
use App\Models\ProductInputDetail\ProductInputDetail;
use App\Models\ProductOutflowDetail\ProductOutflowDetail;

class ProductSizeService
{
    public function enterProduct(array $data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $list = $data['currentQuantity'];
            // guardamos el detalle de cada producto
            $productInput = ProductInput::create([
                'product_id' => $id,
                'seamstress_id' => $data['seamstress_id'],
            ]);
            foreach ($list as $value) {
                $size = ProductSize::find($value['id']);
                $size->quantity += $value['quantity'];
                $size->save();
                ProductInputDetail::create([
                    'product_input_id' => $productInput->id,
                    'size' => $value['size'],
                    'quantity' => $value['quantity'],
                ]);
            }
            return response()->json(['success' => true], 200);
        });
    }

    public function extractProductSale(array $data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $list = $data['currentQuantity'];
            // guardamos el detalle de cada producto
            $productOutflow = ProductOutflow::create([
                'product_id' => $id,
                'client_id' => $data['client_id'],
            ]);
            foreach ($list as $value) {
                $size = ProductSize::find($value['id']);
                if ($size->quantity < $value['quantity']) {
                    $validator = Validator::make([], []);
                    $validator->errors()->add('error', 'Stock insuficiente para el producto');
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                $size->quantity -= $value['quantity'];
                $size->save();
                ProductOutflowDetail::create([
                    'product_outflow_id' => $productOutflow->id,
                    'size' => $value['size'],
                    'quantity' => $value['quantity'],
                ]);
            }
            return response()->json(['success' => true], 200);
        });
    }
}
