<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('teacher_edit');
    }

    public function rules()
    {
        return [

            'specialization' => [
                'string',
            ],
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
