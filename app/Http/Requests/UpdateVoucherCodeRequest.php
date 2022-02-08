<?php

namespace App\Http\Requests;

use App\Models\VoucherCode;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateVoucherCodeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('voucher_code_edit');
    }

    public function rules()
    {
        return [
            'code' => [
                'string',
                'required',
                'unique:voucher_codes,code,' . request()->route('voucher_code')->id,
            ],
            'start_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'end_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
        ];
    }
}
