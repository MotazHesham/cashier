<?php

namespace App\Http\Requests;

use App\Models\StockOperation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStockOperationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('stock_operation_create');
    }

    public function rules()
    {
        return [
            'quantity' => [
                'required',
            ],
            'stock_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
