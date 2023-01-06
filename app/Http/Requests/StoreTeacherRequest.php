<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTeacherRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('teacher_create');
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
            'specialization' => [
                'string',
            ],
        ];
    }
}
