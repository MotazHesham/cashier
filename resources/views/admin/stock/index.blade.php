@extends('layouts.admin')
@section('content')
    @can('stock_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.stock.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.stock.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.stock.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Stock">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.stock.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.stock.fields.item') }}
                        </th>
                        <th>
                            {{ trans('cruds.stock.fields.quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.stock.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.stock.fields.total_cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.stock.fields.entry_date') }}
                        </th>
                        <th>
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($raws as $key => $stock)
                        <tr data-entry-id="{{ $stock->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $stock->id ?? '' }}
                            </td>
                            <td>
                                {{ $stock->item->item ?? '' }}
                            </td>
                            <td>
                                {{ $stock->quantity ?? '' }}
                                {{ $stock->item->measure ? \App\Models\Item::MEASURE_SELECT[$stock->item->measure] : '' }}
                                <br>
                                <span class="badge badge-warning text-white">
                                    الكمية المتبقية
                                    {{ $stock->currentQuantity() }}
                                </span>
                            </td>
                            <td>
                                {{ $stock->price ?? '' }}
                            </td>
                            <td>
                                {{ $stock->total_cost ?? '' }}
                            </td>
                            <td>
                                {{ $stock->entry_date ?? '' }}
                            </td>
                            <td>
                                  @if($stock->production_date)
                                    <span class="badge badge-info">
                                        {{ trans('cruds.stock.fields.production_date') }}
                                        {{ $stock->production_date }}
                                    </span>
                                  @endif
                                  <br>
                                  @if($stock->expiry_date)
                                    <span class="badge badge-danger">
                                        {{ trans('cruds.stock.fields.expiry_date') }}
                                        {{ $stock->expiry_date }}
                                    </span>
                                  @endif
                            </td>
                            <td>

                                @can('stock_operation_create')
                                  @if($stock->currentQuantity())
                                    <button type="button" name="button" class="btn btn-success " onclick="stock_operation_create('{{$stock->id}}')">
                                          {{ trans('cruds.stock_operation.create') }}
                                    </button>
                                  @endif
                                @endcan

                                <button type="button" name="button" class="btn btn-info " onclick="stock_operation_history('{{$stock->id}}')">
                                  سجل السحب
                                </button>

                                @can('stock_delete')
                                  @if($stock->currentQuantity() == $stock->quantity)
                                    <form action="{{ route('admin.stock.destroy', $stock->id) }}" method="POST"
                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                        style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-danger "
                                            value="{{ trans('global.delete') }}">
                                    </form>
                                  @endif
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="stockOpertaionModal"  aria-labelledby="stockOpertaionModalLabel"  >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockOpertaionModalLabel"></h5>
                </div>
                <div class="modal-body">
                  <form action="{{ route('admin.stock_operations.create')}}" method="post">
                    @csrf
                    <input type="hidden" name="stock_id" value="" id='stock_id'>
                    <div class="row">
                      <div class="col-md-4">
                        <input type="number" step="0.01" min="0.01" name="quantity" placeholder="الكمية" class="form-control" value="">
                      </div>
                      <div class="col-md-4">
                        <button type="submit" class="btn btn-info">
                              {{ trans('global.save') }}
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        function stock_operation_create(id){
          $('#stockOpertaionModal').modal('show');
          $('#stock_id').val(id);
          $('#stockOpertaionModal .modal-title').html('{{ trans('cruds.stock_operation.title') }}');
        }

        function stock_operation_history(id){
          $('#stockOpertaionModal .modal-body').html(null);
          $('#stockOpertaionModal').modal('show');
          $('#stockOpertaionModal .modal-title').html('سجل السحب')

          $.ajax({
              type: "POST",
              url: "{{route('admin.stock_operations.history')}}",
              data: {id:id,_token:'{{ csrf_token() }}'},
              success: function(data) {
                $('#stockOpertaionModal .modal-body').html(data);
              },
              error: function(){
                  showFrontendAlert('error', 'حدث خطأ', '');
              }
          });
        }
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Stock:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
