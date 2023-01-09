@extends('layouts.admin')
@section('content')
    <div class="modal fade" id="csvImportStudents" tabindex="-1" role="dialog" aria-labelledby="csvImportStudentsLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="csvImportStudentsLabel">Upload Students</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.students.upload_students')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="excel_file" class="form-control">
                        <button class="btn btn-success" type="submit">Upload</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @can('student_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.students.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.student.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportStudents">
                    {{ trans('global.app_csvImport') }}
                </button>
            </div>
        </div>
    @endcan
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
                                {{ trans('cruds.student.fields.father') }}
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
                                    <br>
                                    <span class="badge badge-info">
                                        {{ trans('cruds.student.fields.grade') }} : {{ $student->grade }}
                                        <br>
                                        {{ trans('cruds.student.fields.class') }} : {{ $student->class }}
                                    </span>
                                </td>
                                <td>
                                    {{ $student->user->email ?? '' }}
                                </td>
                                <td>
                                    {{ $student->user->phone ?? '' }}
                                </td>
                                <td>
                                    {{ $student->father->user->name ?? '' }}
                                </td>
                                <td>

                                    {{-- <a class="btn btn-xs btn-success"
                                        href="{{ route('admin.students.print', $student->id) }}">
                                        طباعة
                                    </a> --}}
                                    @can('student_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.students.show', $student->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('student_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.students.edit', $student->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('student_delete')
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Student:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
