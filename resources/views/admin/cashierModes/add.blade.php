@extends('admin.cashierModes.layout')

@section('content')
    <div style="display:none" id="div-table-receipt">
        <form action="{{ route('admin.cashier-modes.store') }}" method="Post" id="store_form">
            @csrf

            <input type="hidden" name="qr_user_id" id="qr_user_id">
            <div class="partials-scrollable" style="max-height: 43vh">
                <table id="table-receipt" class="table table-borderless table-striped" style="direction: rtl;">
                    <thead>
                        <td>المنتج</td>
                        <td>الكمية</td>
                        <td>الأجمالي</td>
                        <td></td>
                    </thead>
                    <tbody>
                        {{-- ajax call --}}
                    </tbody>
                </table>
            </div>
            <div style="background: white;position: absolute;bottom:0">
                <div class="row">
                    <div class="col-md-6">
                        <input type="number" name="paid_up" min="0" class="form-control" placeholder="المدفوع" required
                        id="paid_up" onkeyup="rest_of_the_amount()" onchange="rest_of_the_amount()" onclick="open_easy_num('paid_up')">
                    </div>
                    <div class="col-md-6">
                        <select name="voucher_code_id" id="" class="form-control">
                            <option value="">اختر كود الخصم</option>
                            @foreach ($vouchercodes as $vouchercode)
                                <option value="{{ $vouchercode->id }}">{{ $vouchercode->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div style="display: flex; justify-content: space-between;">
                    <h4 class="btn btn-lg btn-light" style="background: #ffffff00"><b id="rest_of_the_amount">00.00</b> :
                        المتبقي
                    </h4>
                    <h4 class="btn btn-lg btn-light"><b id="total_cost">00.00</b> : الأجمالي</h4>
                </div>
                <div class="payment_type_container" style="padding:8px">
                    <div class="row text-center mt-3">
                        <div class="col-md-6">
                            <input type="radio" name="payment_type" value="cash" id="cash" checked>
                            <label for="cash" class="payment-type" onclick="qr_code_modal(false)">
                                <i class="payment-type-i fas fa-money-bill" style="font-size:50px;"></i>
                                <br>
                                Cash
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" name="payment_type" value="qr_code" id="qr_code">
                            <label for="qr_code" class="payment-type" onclick="qr_code_modal(true)">
                                <i class="payment-type-i fas fa-qrcode" style="font-size:50px;"></i>
                                <br>
                                QR Code
                            </label>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-3 mb-2">
                        <button class="btn btn-primary" type="submit" id="submit-button"
                            style="border-radius:10px;background: #69becf;border-color: #69becf; padding: 22px; font-size: 34px;">
                            دفع
                        </button>
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

        </form>
    </div>
@endsection
