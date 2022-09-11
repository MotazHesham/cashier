<?php

namespace App\Http\Requests;

use App\Models\Father;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFatherRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('father_edit');
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
                'unique:users,email,' . request()->user_id,
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
