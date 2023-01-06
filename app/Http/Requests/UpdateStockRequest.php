<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStockRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('stock_edit');
    }

    public function rules()
    {
        return [
            'quantity' => [
                'required',
            ],
            'price' => [
                'required',
            ],
            'item_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
