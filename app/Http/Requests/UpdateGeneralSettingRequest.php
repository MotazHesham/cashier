<?php

namespace App\Http\Requests;

use App\Models\GeneralSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGeneralSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('general_setting_edit');
    }

    public function rules()
    {
        return [
            'website_title' => [
                'string',
                'nullable',
            ],
            'phone_1' => [
                'string',
                'nullable',
            ],
            'phone_2' => [
                'string',
                'nullable',
            ], 
            'address' => [
                'string',
                'nullable',
            ],
        ];
    }
}