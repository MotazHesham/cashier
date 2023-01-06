@extends('layouts.father')
@section('content') 
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.order.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Order">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.order.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.code') }}
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.total_cost') }}
                        </th> 
                        <th>
                            {{ trans('cruds.order.fields.user') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                        <tr data-entry-id="{{ $order->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $order->id ?? '' }}
                            </td>
                            <td>
                                {{ $order->code ?? '' }}
                            </td>
                            <td>
                                {{ $order->total_cost ?? '' }}
                            </td>
                            <td>
                                {{ $order->user->name ?? '' }}
                            </td> 
                            <td>   
                                <a class="btn btn-xs btn-primary"
                                    href="{{ route('father.orders.show', $order->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection 
