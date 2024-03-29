@extends('layouts.father')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.payment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Payment">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.payment.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.payment.fields.payment_type') }}
                    </th>
                    <th>
                      {{ trans('cruds.payment.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.payment.fields.payment_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.payment.fields.amount') }}
                    </th>
                    <th>
                        {{ trans('cruds.payment.fields.created_at') }}
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('father.payments.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'id', name: 'id' },
      { data: 'payment_type', name: 'payment_type' },
      { data: 'type', name: 'type' },
      { data: 'payment_status', name: 'payment_status' },
      { data: 'amount', name: 'amount' },
      { data: 'created_at', name: 'created_at' },
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Payment').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
