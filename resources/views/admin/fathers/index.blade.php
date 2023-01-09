@extends('layouts.admin')
@section('content')
    @can('father_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.fathers.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.father.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.father.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Father">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.father.fields.id') }}
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
                                {{ trans('cruds.user.fields.approved') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fathers as $key => $father)
                            <tr data-entry-id="{{ $father->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $father->id ?? '' }}
                                </td>
                                <td>
                                    {{ $father->user->name ?? '' }}
                                </td>
                                <td>
                                    {{ $father->user->email ?? '' }}
                                </td>
                                <td>
                                    {{ $father->user->phone ?? '' }}
                                </td>
                                <td>
                                    <label class="c-switch c-switch-pill c-switch-success">
                                        <input onchange="update_approved(this)" value="{{$father->user_id}}" type="checkbox" class="c-switch-input" {{ ($father->user->approved ? 'checked' : null) }}>
                                        <span class="c-switch-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    @can('father_show')
                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('admin.fathers.show', $father->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('father_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.fathers.edit', $father->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('father_delete')
                                        <form action="{{ route('admin.fathers.destroy', $father->id) }}" method="POST"
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
            let table = $('.datatable-Father:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
