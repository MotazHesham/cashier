@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.attribute.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.attributes.update", [$attribute->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="attribute">{{ trans('cruds.attribute.fields.attribute') }}</label>
                <input class="form-control {{ $errors->has('attribute') ? 'is-invalid' : '' }}" type="text" name="attribute" id="attribute" value="{{ old('attribute', $attribute->attribute) }}" required>
                @if($errors->has('attribute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attribute') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.attribute.fields.attribute_helper') }}</span>
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