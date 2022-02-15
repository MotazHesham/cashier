@extends('layouts.admin')
@section('content')
@can('voucher_code_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.voucher-codes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.voucherCode.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.voucherCode.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-VoucherCode">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.voucherCode.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.voucherCode.fields.code') }}
                        </th>
                        <th>
                            {{ trans('cruds.voucherCode.fields.start_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.voucherCode.fields.end_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.voucherCode.fields.discount') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voucherCodes as $key => $voucherCode)
                        <tr data-entry-id="{{ $voucherCode->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $voucherCode->id ?? '' }}
                            </td>
                            <td>
                                {{ $voucherCode->code ?? '' }}
                            </td>
                            <td>
                                {{ $voucherCode->start_date ?? '' }}
                            </td>
                            <td>
                                {{ $voucherCode->end_date ?? '' }}
                            </td>
                            <td>
                                {{ $voucherCode->discount ?? '' }}
                            </td>
                            <td>
                                @include('partials.datatablesActions',
                                    [
                                        'viewGate' => 'voucher_code_show',
                                        'editGate' => 'voucher_code_edit',
                                        'deleteGate' => 'voucher_code_delete',
                                        'crudRoutePart' =>'voucher-codes',
                                        'row' => $voucherCode
                                    ])   

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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('voucher_code_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.voucher-codes.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-VoucherCode:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection