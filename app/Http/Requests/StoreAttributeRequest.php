<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAttributeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('attribute_create');
    }

    public function rules()
    {
        return [
            'attribute' => [
                'string',
                'required',
            ],
            'slug' => [
                'string',
                'required',
            ],
            'type' => [
                'required',
            ],
        ];
    }
}
