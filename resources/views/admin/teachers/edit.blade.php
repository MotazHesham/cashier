@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.teacher.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.teachers.update", [$teacher->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <input type="hidden" name="user_id" value="{{ $teacher->user->id}}" id="">

            @include('admin.users.partials.edit')

            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.teacher.fields.specialization') }}</label>
                <input class="form-control {{ $errors->has('specialization') ? 'is-invalid' : '' }}" type="text" name="specialization" id="specialization"
                    value="{{ old('specialization', $teacher->specialization) }}" required>
                @if ($errors->has('specialization'))
                    <div class="invalid-feedback">
                        {{ $errors->first('specialization') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.teacher.fields.specialization_helper') }}</span>
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
