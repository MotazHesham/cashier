@extends('layouts.admin')
@section('styles')
<style media="screen">

     .partials-scrollable {
         max-height: 235px;
         height: 100%;
         overflow: scroll;
         overflow-x: hidden;
     }

     .partials-scrollable::-webkit-scrollbar {
         width: 5px;
     }

     .partials-scrollable::-webkit-scrollbar-track {
         background: rgba(0, 0, 0, .0);
         border-radius: 10px;
     }

     .partials-scrollable::-webkit-scrollbar-thumb {
         border-radius: 10px;
         background: #2195f367;
     }

     .partials-scrollable::-webkit-scrollbar-thumb:hover {
         background: #2196f3;
     }

</style>
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.order.title') }}

        <a class="btn btn-success" href="{{route('admin.orders.print2',$order->id)}}">Print Students</a>
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            @if($order->order_from == 'cashier')
              <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.id') }}
                        </th>
                        <td>
                            {{ $order->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.code') }}
                        </th>
                        <td>
                            {{ $order->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.paid_up') }}
                        </th>
                        <td>
                            {{ $order->paid_up }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.total_cost') }}
                        </th>
                        <td>
                            {{ $order->total_cost }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.voucher_code') }}
                        </th>
                        <td>
                            {{ $order->voucher_code->code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.order.fields.products') }}
                        </th>
                        <td>
                          <table id="table-receipt" class="table table-borderless table-striped" style="direction: rtl;">
                              <thead>
                                  <td>المنتج</td>
                                  <td>الكمية</td>
                                  <td>الأجمالي</td>
                              </thead>
                              <tbody>
                                @foreach ($order->products as $order_product)
                                    @php
                                        $extra_price = 0;
                                        $product_price = $order_product->product->price ?? 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $order_product->product->name ?? '' }}
                                            <br>
                                            @foreach (json_decode($order_product->attributes) as $row)
                                                @php
                                                    $extra_price += $row->price;
                                                @endphp
                                                <b class="badge bg-info">{{ $row->variant }}</b>
                                            @endforeach
                                            @php
                                                $product_cost_with_extra = $extra_price + $product_price;
                                            @endphp
                                        </td>
                                        <td>
                                            {{ $order_product->quantity }}
                                        </td>
                                        <td id="receipt-product-cost-{{ Session::get('counter') }}" class="receipt-product-cost">
                                            {{ $product_cost_with_extra * $order_product->quantity }}
                                        </td>
                                    </tr>
                                @endforeach
                              </tbody>
                        </td>
                    </tr>
                </tbody>
            </table>
            @elseif($order->order_from == 'teacher')
            <div class="row">

              @foreach($order->products()->get()->groupBy('user_id') as $user_id => $products)
                @php
                  $user = \App\Models\User::find($user_id);
                  $payment_type = $products[0]->payment_type ?? '';
                @endphp
                @if($user)
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-6">
                          <h3>{{$user->name}}</h3>
                        </div>
                        <div class="col-md-6">
                          @if($order->viewed)
                            @if($payment_type)
                            <span class="badge @if($payment_type == 'cash') badge-success @else badge-info @endif">تم الدفع بواسطة :{{\App\Models\Order::PAYMENT_TYPE_SELECT[$payment_type]}}</span>
                            @else
                            <form class="" action="{{route('admin.orders.pay_user')}}" method="post">
                              @csrf
                              <input type="hidden" name="order_id" value="{{$order->id}}">
                              <input type="hidden" name="user_id" value="{{$user->id}}">

                              <span>
                                <label for="cash-{{$user_id}}">Cash</label>
                                <input type="radio" name="payment_type" value="cash" id="cash-{{$user_id}}" onclick="pay_user(false,'{{$user_id}}','{{$order->id}}')">

                                <label for="qr-{{$user_id}}">QR Code</label>
                                <input type="radio" name="payment_type" value="qr_code" id="qr-{{$user_id}}" onclick="pay_user(true,'{{$user_id}}','{{$order->id}}')">
                              </span>

                              <input type="submit" name="save" value="Save" class="btn btn-info" id="submit-button-{{$user_id}}">
                            </form>
                            @endif
                          @endif
                        </div>
                      </div>

                    </div>
                    <div class="card-body">
                      <div class="partials-scrollable" style="max-height: 43vh">
                          <table id="table-receipt" class="table table-borderless table-striped" style="direction: rtl;">
                              <thead>
                                  <td>المنتج</td>
                                  <td>الكمية</td>
                                  <td>الأجمالي</td>
                              </thead>
                              <tbody>
                                  @foreach($products as $order_product)
                                    @php
                                        $extra_price = 0;
                                        $product_price = $order_product->product->price ?? 0;
                                        Session::put('counter', Session::get('counter') + 1);
                                    @endphp
                                    <tr id="receipt-product-{{ Session::get('counter') }}">
                                        <td>
                                            {{ $order_product->product_name }}
                                            <br>

                                            @foreach (json_decode($order_product->attributes) as $row)
                                                @php
                                                    $extra_price += $row->price;
                                                @endphp
                                                <b class="badge bg-info">{{ $row->variant }}</b>
                                            @endforeach
                                            @php
                                                $product_cost_with_extra = $extra_price + $product_price;
                                            @endphp
                                        </td>
                                        <td>
                                          {{$order_product->quantity}}
                                        </td>
                                        <td id="receipt-product-cost-{{ Session::get('counter') }}" class="receipt-product-cost">
                                            {{ $product_cost_with_extra * $order_product->quantity }}
                                        </td>

                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>

                    </div>
                  </div>
                </div>
                @endif
              @endforeach
            </div>
            @endif
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.orders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!--Container Main end-->
<div class="modal fade" id="QRModal"  aria-labelledby="QRModalLabel"  >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="QRModalLabel">Qr Scanner</h5>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
  function pay_user(status,user_id,order_id){
    if(status){
      $.ajax({
          type: "POST",
          url: '{{ route('admin.orders.qr_scanner') }}',
          data:{_token:'{{ csrf_token() }}',user_id:user_id,order_id:order_id},
          success: function(data) {
              $('#submit-button-'+user_id).css('visibility','hidden');
              $('#QRModal').modal('show');
              $('#QRModal .modal-body').html(null);
              $('#QRModal .modal-body').html(data);
          }
      });
    }else{
        $('#submit-button-'+user_id).css('visibility','visible');
    }
  }
</script>

@endsection
