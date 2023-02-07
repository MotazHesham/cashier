@extends('layouts.father')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.student.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Student">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.student.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.email') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.phone') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $key => $student)
                            <tr data-entry-id="{{ $student->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $student->id ?? '' }}
                                </td>
                                <td>
                                    {{ $student->user->name ?? '' }}
                                </td>
                                <td>
                                    {{ $student->user->email ?? '' }}
                                </td>
                                <td>
                                    {{ $student->user->phone ?? '' }}
                                </td>
                                <td>
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('father.students.show', $student->id) }}">
                                            {{ trans('global.view') }}
                                        </a>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
