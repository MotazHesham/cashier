@extends('layouts.father')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('father.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.id') }}
                        </th>
                        <td>
                            {{ $order->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.code') }}
                        </th>
                        <td>
                            {{ $order->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.user') }}
                        </th>
                        <td>
                            {{ $order->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.paid_up') }}
                        </th>
                        <td>
                            {{ $order->paid_up }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $order->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.voucher_code') }}
                        </th>
                        <td>
                            {{ $order->voucher_code->code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.products') }}
                        </th>
                        <td>
                            @foreach($order->products as $key => $product) 
                                    
                                    
                                @foreach (json_decode($product->attributes) as $row) 
                                    @php
                                        $attribute = \App\Models\Attribute::find($row->attribute_id)->attribute ?? '';
                                    @endphp     
                                @endforeach   
                                
                                <span class="badge badge-success">{{ $product->product_name }} <br>
                                    {{ $attribute }}:{{ $row->variant }}</span>
                                    <span class="badge badge-info">x{{ $product->quantity }}</span>
                                    <span class="badge badge-warning">={{ $product->total_cost }}</span>
                                <hr>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('father.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection