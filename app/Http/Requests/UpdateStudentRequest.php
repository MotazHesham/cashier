<?php

namespace App\Http\Requests;

use App\Models\Student;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('student_edit');
    }

    public function rules()
    {
        return [
            'father_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'required',
            ],
            'grade' => [
                'string',
                'required',
            ],
            'class' => [
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
