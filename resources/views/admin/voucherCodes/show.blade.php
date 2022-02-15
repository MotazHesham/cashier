@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.voucherCode.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.voucher-codes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.voucherCode.fields.id') }}
                        </th>
                        <td>
                            {{ $voucherCode->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.voucherCode.fields.code') }}
                        </th>
                        <td>
                            {{ $voucherCode->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.voucherCode.fields.discount') }}
                        </th>
                        <td>
                            {{ $voucherCode->discount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.voucherCode.fields.description') }}
                        </th>
                        <td>
                            {{ $voucherCode->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.voucherCode.fields.start_date') }}
                        </th>
                        <td>
                            {{ $voucherCode->start_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.voucherCode.fields.end_date') }}
                        </th>
                        <td>
                            {{ $voucherCode->end_date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.voucher-codes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#voucher_code_orders" role="tab" data-toggle="tab">
                {{ trans('cruds.order.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="voucher_code_orders">
            @includeIf('admin.voucherCodes.relationships.voucherCodeOrders', ['orders' => $voucherCode->voucherCodeOrders])
        </div>
    </div>
</div>

@endsection