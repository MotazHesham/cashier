<?php

namespace App\Http\Requests;

use App\Models\Father;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFatherRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('father_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:fathers,id',
        ];
    }
}
