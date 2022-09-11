<?php

namespace App\Http\Requests;

use App\Models\Father;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFatherRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('father_create');
    }

    public function rules()
    {
        return [

            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users',
            ],
            'password' => [
                'required',
            ],
            'identity' => [
                'array',
            ],
            'phone' => [
                'string',
                'required',
            ],
        ];
    }
}
