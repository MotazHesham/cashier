
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
                {{ trans('cruds.order.fields.payment_type') }}
            </th>
            <td>
                {{ $order->payment_type ? \App\Models\Order::PAYMENT_TYPE_SELECT[$order->payment_type] : ''}}
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
