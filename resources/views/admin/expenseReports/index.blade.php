@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col">
        <h3 class="page-title">{{ trans('cruds.expenseReport.reports.title') }}</h3>

        <form method="get">
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label" for="y">{{ trans('global.year') }}</label>
                    <select name="y" id="y" class="form-control">
                        @foreach(array_combine(range(date("Y"), 1900), range(date("Y"), 1900)) as $year)
                            <option value="{{ $year }}" @if($year===old('y', Request::get('y', date('Y')))) selected @endif>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 form-group">
                    <label class="control-label" for="m">{{ trans('global.month') }}</label>
                    <select name="m" for="m" class="form-control">
                        @foreach(cal_info(0)['months'] as $key => $month)
                            <option value="{{ $key }}" @if($key == $m)) selected @endif>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <label class="control-label">&nbsp;</label><br>
                    <button class="btn btn-primary" type="submit">{{ trans('global.filterDate') }}</button>
                    <button class="btn btn-success" type="submit" name="print">تقرير</button>
                </div>
            </div>
        </form>
        <form method="get">
            <div class="row">
                <div class="col-3 form-group">
                    <label class="control-label" for="start_date">بداية التاريخ</label>
                    <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" required>
                </div>
                <div class="col-3 form-group">
                    <label class="control-label" for="end_date">نهاية التاريخ</label>
                    <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" required>
                </div>
                <div class="col-4">
                    <label class="control-label">&nbsp;</label><br>
                    <button class="btn btn-primary" type="submit">{{ trans('global.filterDate') }}</button>
                    <button class="btn btn-success" type="submit" name="print">تقرير</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.expenseReport.reports.incomeReport') }}
    </div>

    <div class="card-body">
      <h3 class="text-center">
        @if($start_date)
          <span class="badge badge-info">{{ $start_date }}</span>
          ->
          <span class="badge badge-warning text-white"> {{ $end_date }}</span>
        @else
          {{$m}} - {{$y}}
        @endif
      </h3>
        <div class="row">
            <div class="col">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>{{ trans('cruds.expenseReport.reports.income') }}</th>
                        <td>{{ number_format(($incomesTotal + $ordersTotal + $payments_charge), 2) }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.expenseReport.reports.expense') }}</th>
                        <td>{{ number_format(($expensesTotal + $stockTotal + $payments_withdraw), 2) }}</td>
                    </tr>
                    <tr style="border-color: green;border-width: 2px;border-style: dashed;">
                        <th>{{ trans('cruds.expenseReport.reports.profit') }}</th>
                        <td>{{ number_format($profit, 2) }}</td>
                    </tr>
                </table>
                <hr>
                <h4 style="padding:15px">الطالبات المحذوفة</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <td>الطلب</td>
                        <td>الأجمالي</td>
                        <td>وقت الحذف</td>
                    </thead>
                    <tbody>
                        @foreach($trashedOrdersIncomes as $trash_order)
                            <tr>
                                <td>
                                    {{ $trash_order->code }}
                                </td>
                                <td>
                                    {{ $trash_order->total_cost }}
                                </td>
                                <td>
                                    {{ $trash_order->deleted_at ? Carbon\Carbon::parse($trash_order->deleted_at)->format(config('panel.date_format') . ' ' .config('panel.time_format')) : null }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tr>
                        <th>{{ trans('cruds.order.fields.total_cost') }}</th>
                        <td colspan="2">{{ number_format($trashedOrdersTotal, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col">
                <table class="table table-bordered table-striped">
                    <tr style="border-color: green;border-width: 2px;border-style: ridge;">
                        <th>{{ trans('cruds.expenseReport.reports.incomeByCategory') }}</th>
                        <th>{{ number_format(($incomesTotal + $ordersTotal + $payments_charge), 2) }}</th>
                    </tr>
                    @foreach($incomesSummary as $inc)
                        <tr>
                            <th>
                              {{ $inc['name'] }}
                              @if($inc['expanded'] ?? '')
                                @foreach($inc['items'] as $item)
                                <br>
                                <span class="badge badge-light">{{$item['name']}}</span>  (<span>{{number_format($item['amount'], 2)}}</span>)
                                @endforeach
                              @endif
                            </th>
                            <td>{{ number_format($inc['amount'], 2) }}</td>

                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col">
                <table class="table table-bordered table-striped">
                    <tr style="border-color: red;border-width: 2px;border-style: ridge;">
                        <th>{{ trans('cruds.expenseReport.reports.expenseByCategory') }}</th>
                        <th>{{ number_format(($expensesTotal + $stockTotal + $payments_withdraw), 2) }}</th>
                    </tr>
                    @foreach($expensesSummary as $inc)
                        <tr>
                            <th>
                              {{ $inc['name'] }}
                              @if($inc['details'] ?? '')
                                <button type="button" class="btn btn-info btn-xs text-center" onclick="showDetailsModal()">أظهار التفاصيل</button>
                              @endif
                            </th>
                            <td>{{ number_format($inc['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="showMoreModal"  aria-labelledby="showMoreModalLabel"  >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showMoreModalLabel">
                تفاصيل الأصناف المضافة في المخزن
                (
                  @if($start_date)
                    <span class="badge badge-info">{{ $start_date }}</span>
                    ->
                    <span class="badge badge-warning text-white"> {{ $end_date }}</span>
                  @else
                    {{$m}} - {{$y}}
                  @endif
                  )
                </h5>
            </div>
            <div class="modal-body">

                  <table class=" table table-bordered table-striped table-hover datatable ">
                      <thead>
                        <tr>
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
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($stockByItems as $key => $item)
                          @foreach($item as $stock)
                            <tr>
                              <td>
                                {{ \App\Models\Item::find($key)->item ?? '' }}
                              </td>
                              <td>
                                {{ $stock->quantity }}
                              </td>
                              <td>
                                {{ $stock->price }}
                              </td>
                              <td>
                                {{ $stock->total_cost }}
                              </td>
                              <td>
                                {{ $stock->entry_date }}
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
                            </tr>
                          @endforeach
                        @endforeach
                      </tbody>
                  </table>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
<script>
    function showDetailsModal(){
      $('#showMoreModal').modal('show');
    }
    $('.date').datepicker({
        autoclose: true,
        dateFormat: "{{ config('panel.date_format_js') }}"
      })
</script>
@stop
