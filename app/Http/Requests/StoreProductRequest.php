<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'price' => [
                'required',
            ],
            'category_id' => [
                'required',
                'integer',
            ],
            'attributes.*' => [
                'integer',
            ],
            'attributes' => [
                'array',
            ],
            'photo' => [
                'required',
            ],
        ];
    }
}
