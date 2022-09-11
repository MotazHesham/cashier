<?php

namespace App\Http\Requests;

use App\Models\School;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('school_edit');
    }

    public function rules()
    {
        return [
            'school_name' => [
                'string',
                'required',
            ],
            'address' => [
                'required',
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
