@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.order.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}" required>
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="paid_up">{{ trans('cruds.order.fields.paid_up') }}</label>
                <input class="form-control {{ $errors->has('paid_up') ? 'is-invalid' : '' }}" type="number" name="paid_up" id="paid_up" value="{{ old('paid_up', '') }}" step="0.01">
                @if($errors->has('paid_up'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paid_up') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.paid_up_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_cost">{{ trans('cruds.order.fields.total_cost') }}</label>
                <input class="form-control {{ $errors->has('total_cost') ? 'is-invalid' : '' }}" type="number" name="total_cost" id="total_cost" value="{{ old('total_cost', '') }}" step="0.01">
                @if($errors->has('total_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.total_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="voucher_code_id">{{ trans('cruds.order.fields.voucher_code') }}</label>
                <select class="form-control select2 {{ $errors->has('voucher_code') ? 'is-invalid' : '' }}" name="voucher_code_id" id="voucher_code_id">
                    @foreach($voucher_codes as $id => $entry)
                        <option value="{{ $id }}" {{ old('voucher_code_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('voucher_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('voucher_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.voucher_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="products">{{ trans('cruds.order.fields.products') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('products') ? 'is-invalid' : '' }}" name="products[]" id="products" multiple required>
                    @foreach($products as $id => $product)
                        <option value="{{ $id }}" {{ in_array($id, old('products', [])) ? 'selected' : '' }}>{{ $product }}</option>
                    @endforeach
                </select>
                @if($errors->has('products'))
                    <div class="invalid-feedback">
                        {{ $errors->first('products') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.products_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection