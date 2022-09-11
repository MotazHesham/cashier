@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.student.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <div class="text-center mb-3">
                    {!! QrCode::size(150)->generate($user->id); !!}
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
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
