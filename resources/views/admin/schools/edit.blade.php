@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.school.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.schools.update", [$school->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <input type="hidden" name="user_id" value="{{ $school->user->id}}" id="">

            @include('admin.users.partials.edit')

            <div class="form-group">
                <label class="required" for="school_name">{{ trans('cruds.school.fields.school_name') }}</label>
                <input class="form-control {{ $errors->has('school_name') ? 'is-invalid' : '' }}" type="text" name="school_name" id="school_name" value="{{ old('school_name', $school->school_name) }}" required>
                @if($errors->has('school_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('school_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.school.fields.school_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="address">{{ trans('cruds.school.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" required>{{ old('address', $school->address) }}</textarea>
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.school.fields.address_helper') }}</span>
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
