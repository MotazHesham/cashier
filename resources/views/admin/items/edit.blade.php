@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.item.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.items.update", [$item->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="item">{{ trans('cruds.item.fields.item') }}</label>
                <input class="form-control {{ $errors->has('item') ? 'is-invalid' : '' }}" type="text" name="item" id="item" value="{{ old('item', $item->item) }}" required>
                @if($errors->has('item'))
                    <div class="invalid-feedback">
                        {{ $errors->first('item') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.item.fields.item_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.item.fields.measure') }}</label>
                <select class="form-control {{ $errors->has('measure') ? 'is-invalid' : '' }}" name="measure" id="measure" required>
                    <option value disabled {{ old('measure', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Item::MEASURE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('measure', $item->measure) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('measure'))
                    <div class="invalid-feedback">
                        {{ $errors->first('measure') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.item.fields.measure_helper') }}</span>
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
