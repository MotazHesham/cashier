@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.student.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.students.update", [$student->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <input type="hidden" name="user_id" value="{{ $student->user->id}}" id="">

            <div class="form-group">
                <label class="required" for="father_id">{{ trans('cruds.student.fields.father') }}</label>
                <select class="form-control select2 {{ $errors->has('father') ? 'is-invalid' : '' }}" name="father_id" id="father_id" required>
                    @foreach($fathers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('father_id') ? old('father_id') : $student->father->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('father'))
                    <div class="invalid-feedback">
                        {{ $errors->first('father') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.father_helper') }}</span>
            </div>

            @include('admin.users.partials.edit')

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
