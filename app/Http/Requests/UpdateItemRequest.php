<?php

namespace App\Http\Requests;

use App\Models\Item;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateItemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('item_edit');
    }

    public function rules()
    {
        return [
            'item' => [
                'string',
                'required',
            ],
            'type' => [
                'string',
                'required',
            ],
        ];
    }
}
