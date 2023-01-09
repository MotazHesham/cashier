@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.student.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="row">

                    <div class="col-md-6" >

                        <div class="card">
                            <div class="card-body">
                                <div class="row" >
                                    <div class="col-md-4 text-center">
                                        {!! QrCode::size(120)->generate($user->id) !!}
                                        {{-- <img src="{{$setting->logo ? $setting->logo->getUrl('')  : ''}}" width="50" alt=""> --}}
                                    </div>
                                    <div class="col-md-8">
                                        <div class="text-center">
                                            <img src="{{$user->photo ? $user->photo->getUrl('preview') : ''}}" class="rounded" width="100" >
                                            <h3>{{ $user->name }}</h3>
                                            <h5>{{ $student->grade ? \App\Models\Student::GRADE_SELECT[$student->grade] : '' }} - {{ $student->class ? \App\Models\Student::CLASS_SELECT[$student->class] : '' }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.student.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $student->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.student.fields.grade') }}
                                    </th>
                                    <td>
                                        {{ $student->grade ? \App\Models\Student::GRADE_SELECT[$student->grade] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.student.fields.class') }}
                                    </th>
                                    <td>
                                        {{ $student->class ? \App\Models\Student::CLASS_SELECT[$student->class] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.student.fields.father') }}
                                    </th>
                                    <td>
                                        {{ $student->father->user->name ?? '' }}
                                    </td>
                                </tr>

                                @include('admin.users.partials.show')

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        @include('admin.users.partials.wallet')
                    </div>

                </div>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.students.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
