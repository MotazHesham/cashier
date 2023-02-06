@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.student.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.students.store") }}" enctype="multipart/form-data">
            @csrf


            <div class="form-group">
                <label class="required" for="father_id">{{ trans('cruds.student.fields.father') }}</label>
                <select class="form-control select2 {{ $errors->has('father') ? 'is-invalid' : '' }}" name="father_id" id="father_id" required>
                    @foreach($fathers as $id => $entry)
                        <option value="{{ $id }}" {{ old('father_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('father'))
                    <div class="invalid-feedback">
                        {{ $errors->first('father') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.father_helper') }}</span>
            </div>


            <div class="form-group">
                <label class="required">{{ trans('cruds.student.fields.grade') }}</label>
                <select class="form-control {{ $errors->has('grade') ? 'is-invalid' : '' }}" name="grade" id="grade" required>
                    <option value disabled {{ old('grade', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Student::GRADE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('grade', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('grade'))
                    <div class="invalid-feedback">
                        {{ $errors->first('grade') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.grade_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required">{{ trans('cruds.student.fields.class') }}</label>
                <select class="form-control {{ $errors->has('class') ? 'is-invalid' : '' }}" name="class" id="class" required>
                    <option value disabled {{ old('class', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Student::CLASS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('class', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('class'))
                    <div class="invalid-feedback">
                        {{ $errors->first('class') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.class_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required">{{ trans('cruds.student.fields.father_email') }}</label>
                <input class="form-control {{ $errors->has('father_email') ? 'is-invalid' : '' }}" type="email" name="father_email"
                    id="father_email" value="{{ old('father_email') }}" required>
                @if ($errors->has('father_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('father_email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.student.fields.father_email_helper') }}</span>
            </div>
            @include('admin.users.partials.create')

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
