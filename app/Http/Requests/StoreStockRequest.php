<?php

namespace App\Http\Requests;

use App\Models\Stock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStockRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('stock_create');
    }

    public function rules()
    {
        return [
            'entry_date' => [
                'required',
            ],
            'price' => [
                'required',
            ],
            'quantity' => [
                'required',
            ],
            'item_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
