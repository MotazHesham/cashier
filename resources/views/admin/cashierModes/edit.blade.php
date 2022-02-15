@extends('admin.cashierModes.layout')

@section('content')
    @php
    $date = explode(' ', $order->created_at, 2);
    $code = explode('-', $order->code);
    @endphp
    <div style="display: flex;justify-content:space-around;">
        <div class="badge bg-light text-dark">Date: <b>{{ $date[0] ?? '' }}</b></div>
        <div class="badge bg-light text-dark">Order Code: <b>{{ $code[1] }}</b></div>
        <div class="badge bg-light text-dark">Time: <b>{{ $date[1] ?? '' }}</b></div>
    </div>
    <hr>
    <div style="display:block" id="div-table-receipt">
        <form action="{{ route('admin.cashier-modes.update') }}" method="Post">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}" id="">
            <div class="partials-scrollable" style="max-height: 43vh">
                <table id="table-receipt" class="table table-borderless table-striped" style="direction: rtl;">
                    <thead>
                        <td>المنتج</td>
                        <td>الكمية</td>
                        <td>الأجمالي</td>
                        <td></td>
                    </thead>
                    <tbody>
                        @foreach ($order->products as $order_product)
                            @php
                                $extra_price = 0;
                                $product_price = $order_product->product->price ?? 0;
                                Session::put('counter', Session::get('counter') + 1);
                            @endphp
                            <tr id="receipt-product-{{ Session::get('counter') }}">
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
                                    <input style="width: 55px;text-align:center" type="number" class="form-control" min="1"
                                        step="1" name="products[{{ Session::get('counter') }}][quantity]"
                                        value="{{ $order_product->quantity }}"
                                        onkeyup="change_quantity(this,{{ Session::get('counter') }},{{ $product_cost_with_extra }})" required>
                                </td>
                                <td id="receipt-product-cost-{{ Session::get('counter') }}" class="receipt-product-cost">
                                    {{ $product_cost_with_extra * $order_product->quantity }}
                                </td>
                                <td>
                                    <select name="products[{{ Session::get('counter') }}][attributes][]" id=""
                                        style="display: none" multiple>
                                        @foreach (json_decode($order_product->attributes) as $row)
                                            <option value="{{ $row->variant }}" selected>{{ $row->variant }}</option>
                                        @endforeach
                                    </select> {{-- form purpose --}}

                                    <input type="hidden" name="products[{{ Session::get('counter') }}][product_id]"
                                        value="{{ $order_product->product->id ?? '' }}">
                                    <input type="hidden" name="products[{{ Session::get('counter') }}][product_cost]"
                                        value="{{ $product_cost_with_extra }}">
                                    <button class="btn btn-outline-danger" type="button"
                                        onclick="removeTr({{ Session::get('counter') }})"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="background: white;position: absolute;bottom:0">
                <div class="row">
                    <div class="col-md-6">
                        <input type="number" name="paid_up" min="0" class="form-control" placeholder="المدفوع" required
                            id="paid_up" onkeyup="rest_of_the_amount()" value="{{ $order->paid_up }}">
                    </div>
                    <div class="col-md-6">
                        <select name="voucher_code_id" id="" class="form-control">
                            <option value="">اختر كود الخصم</option>
                            @foreach ($vouchercodes as $vouchercode)
                                <option value="{{ $vouchercode->id }}" @if ($order->voucher_code_id == $vouchercode->id) selected @endif>
                                    {{ $vouchercode->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="btn btn-lg btn-light" style="background: #ffffff00"><b
                            id="rest_of_the_amount">{{ $order->paid_up - $order->total_cost }}</b> :
                        المتبقي
                    </h4>
                    <h4 class="btn btn-lg btn-light"><b id="total_cost">{{ $order->total_cost }}</b> : الأجمالي</h4>
                </div>
                <div class="payment_type_container" style="padding:8px">
                    <div class="row text-center mt-3">
                        <div class="col-md-6">
                            <input type="radio" name="payment_type" value="cash" id="cash" checked>
                            <label for="cash" class="payment-type">
                                <i class="payment-type-i fas fa-money-bill" style="font-size:50px;"></i>
                                <br>
                                Cash
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" name="payment_type" value="credit" id="credit">
                            <label for="credit" class="payment-type">
                                <i class="payment-type-i fas fa-credit-card" style="font-size:50px;"></i>
                                <br>
                                Credit Card
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @can('order_edit')
                                <div class="d-grid gap-2 mt-3 mb-2">
                                    <button class="btn btn-success btn-block" type="submit"
                                        style="background: var(--payment-color);border-color:var(--payment-color)">
                                        تحديث
                                    </button>
                                </div>
                            @endcan
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid gap-2 mt-3 mb-2">
                                @can('order_delete')
                                    <button class="btn btn-outline-danger btn-block" type="submit">
                                        حذف
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
