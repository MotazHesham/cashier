@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.school.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.schools.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.school.fields.id') }}
                        </th>
                        <td>
                            {{ $school->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.school.fields.school_name') }}
                        </th>
                        <td>
                            {{ $school->school_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.school.fields.address') }}
                        </th>
                        <td>
                            {{ $school->address }}
                        </td>
                    </tr>

                    @include('admin.users.partials.show')

                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.schools.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
