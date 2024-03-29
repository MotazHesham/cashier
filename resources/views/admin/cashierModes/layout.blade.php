<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Mode</title>
    <link rel="stylesheet" href="{{ asset('cashier/vendor/bootstrap/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cashier/vendor/bootstrap/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cashier/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cashier/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('cashier/css/easy-numpad.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/all.css') }}">
    <style>
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
            background: var(--main-color-2);
        }

        .partials-scrollable::-webkit-scrollbar-thumb:hover {
            background: var(--main-color);
        }

        .nav-items a {
            margin: 0 10px;
        }

        .btn-light {
            background-color: #ECF0FA;
        }

        .payment-type {
            background: #ECF0FA;
            padding: 5px;
            margin: 5px;
            border-radius: 15px;
            cursor: pointer;
            width: 150px
        }

        body {
            font-family: system-ui;
        }
    </style>
</head>

<body id="body-pd" class="body-pd" onafterprint="go_full_screen();">

    <header class="header body-pd" id="header">
        <div class="header_toggle">
            <div class="nav-items">
                @can('order_access')
                    <a class="btn btn-lg btn-light" href="{{ route('admin.orders.index') }}" style="position: relative">
                        <span class="badge bg-warning" style="position: absolute;top:0;right:0;font-size:13px">{{ \App\Models\Order::where('viewed',0)->count() ?? ''}}</span>
                        <i class="fas fa-receipt"></i>
                        <span class="nav_name">Orders</span>
                    </a>
                @endcan
                <a class="btn btn-lg btn-light" href="{{ route('admin.cashier-modes.index') }}">
                    <i class="fas fa-redo-alt"></i>
                    <span class="nav_name">Refresh</span>
                </a>
                <a class="btn btn-lg btn-light" onclick="go_full_screen()">
                    <i class="fas fa-expand"></i>
                    <span class="nav_name">Expand</span>
                </a>
                <a class="btn btn-lg btn-light">
                    <input type="checkbox" id="printing" name="printing" value="1" checked
                        onclick="change_printing()">
                    <label for="printing">Printing</label><br>
                </a>
                <a class="btn btn-lg btn-light" style="border-radius: 35px;position:absolute;right:0">
                    <form action="{{ route('admin.cashier-modes.edit') }}">
                        <input style="border-radius: 35px" type="text" class="form-control" name="code"
                            value="{{ date('Ymd', strtotime('now')) }}-" id="">
                    </form>
                </a>

            </div>
        </div>
    </header>
    @php
        $setting = \App\Models\GeneralSetting::first();
    @endphp
    <div class="l-navbar navbar-show partials-scrollable" style="max-height: 100%" id="nav-bar">
        <h3 class="text-center">{{ $setting->website_title ?? '' }}</h3>

        <hr>
        @yield('content')
    </div>

    <!--Container Main start-->
    <div class="accordion" id="accordionExample">
        <div class="owl-carousel owl-slider-custom ">
            @foreach ($categories as $category)
                @php
                    if ($category->photo) {
                        $category_image = $category->photo->getUrl('preview');
                    } else {
                        $category_image = asset('noimage.jpg');
                    }
                @endphp
                <div class="item" id="heading{{ $category->id }}">
                    <div class="@if (!$loop->first) collapsed @endif" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="true"
                        aria-controls="collapse{{ $category->id }}">
                        <img src="{{ $category_image }}" alt="{{ $category->name }}">
                        <p>{{ $category->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @foreach ($categories as $category)
            <div id="collapse{{ $category->id }}" class="collapse @if ($loop->first) show @endif"
                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="row text-center justify-content-md-center">
                    @forelse($category->products as $product)
                        @php
                            if ($product->photo) {
                                $product_image = $product->photo->getUrl('preview');
                            } else {
                                $product_image = asset('noimage.jpg');
                            }
                        @endphp
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <form class="card add-product" method="post">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div style="box-shadow: 1px 9px 10px #e5e5e585; border-radius: 15px;">
                                    <div class="box">
                                        <img class="card-img-top" src="{{ $product_image }}" alt="Card image cap">
                                        <h3 class="card-title">
                                            {{ $product->name }}
                                            <span class="title">
                                                <?php echo nl2br($product->description ?? ''); ?>
                                            </span>
                                            <b class="filled">{{ $product->price }}LE</b>
                                        </h3>
                                    </div>
                                    <div class="counter qty">
                                        <span class="minus bg-dark" data-id="{{ $product->id }}">-</span>
                                        <input type="number" class="form-control count"
                                            id="quantity-{{ $product->id }}" style="width: 55px" name="quantity"
                                            value="1" min="1" step="1" required
                                            onclick="open_easy_num('quantity-{{ $product->id }}')">
                                        <span class="plus bg-dark" data-id="{{ $product->id }}">+</span>
                                    </div>
                                </div>
                                <div class="partials-scrollable">
                                    <div class="row">
                                        @foreach (json_decode($product->attributes_options) as $key => $attribute_option)
                                            @php
                                                $attribute = \App\Models\Attribute::find($attribute_option->attribute_id);
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="card-body attribute;">
                                                    <p class="card-text">{{ $attribute->attribute ?? '' }}
                                                    </p>
                                                    <span style="display: flex;flex-wrap: wrap;">
                                                        @foreach ($attribute_option->values as $key2 => $value)
                                                            <input
                                                                @if ($attribute && $attribute->type == 'single') type="radio"
                                                                @if ($key2 == 0)
                                                                    checked @endif
                                                            @else type="checkbox" @endif
                                                            name="attributes[{{ $attribute_option->attribute_id }}][]"
                                                            value="{{ $value }}"
                                                            id="{{ $product->id . $value }}">
                                                            <label
                                                                for="{{ $product->id . $value }}">{{ $value }}</label>
                                                        @endforeach
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg"
                                    style="border-radius: 10px">أضف</button>
                            </form>
                        </div>
                        @empty
                            <div class="alert alert-info" style="margin: 10%"> No Products For This Category Right
                                Now....
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>


        <!--Container Main end-->
        <div class="modal fade" id="QRModal" aria-labelledby="QRModalLabel">
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

        @include('sweetalert::alert')

        <script src="{{ asset('cashier/vendor/js/jquery.min.js') }}"></script>
        <script src="{{ asset('cashier/js/easy-numpad.js') }}"></script>
        <script src="{{ asset('cashier/vendor/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('cashier/vendor/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('cashier/js/slide.js') }}"></script>
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{ asset('dashboard_offline/css/sweetalert2.min.css') }}">
        <script src="{{ asset('dashboard_offline/js/sweetalert2.all.min.js') }}"></script>
        {{-- Sweet alert delete --}}


        <script src="{{ asset('js/JSPrintManager.js') }}"></script>

        <script>
            function showFrontendAlert(type, title, message) {
                swal({
                    title: title,
                    text: message,
                    type: type,
                    showConfirmButton: 'Okay',
                    timer: 3000
                });
            }
        </script>
        <script>
            function change_printing() {
                var printing = $('#printing').val();
                if (printing == 1) {
                    $('#printing').val(0);
                } else {
                    $('#printing').val(1);
                }
            }

            $('#store_form').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    type: $(this).attr("method"),
                    url: $(this).attr("action"),
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#QRModal').modal('hide');
                        $('#QRModal .modal-body').html(null);
                        $('#div-table-receipt').css('display', 'none');
                        $('#table-receipt tbody').html(null);
                        $('#paid_up').val(null);
                        $('#rest_of_the_amount').html('00.00');
                        var printing = $('#printing').val();

                        if (data['status']) {
                            if (printing == 1) {
                                console.log(data);
                                window.myAPI.sendDataToMainProcess([data['cashier_printer'],data['kitchen_printer'],data['link'],data['cashier_printer_copies'],data['kitchen_printer_copies']]);
                                // window.open(data['link'], "_blank");
                            } else {
                                showFrontendAlert('success', 'تم أضافة الطلب بنجاح', '');
                            }
                        } else {
                            showFrontendAlert('error', data['message'], '');
                        }
                    },
                    error: function() {
                        showFrontendAlert('error', 'حدث خطأ', '');
                    }
                });
            })

            $('#update_form').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    type: $(this).attr("method"),
                    url: $(this).attr("action"),
                    data: $(this).serialize(),
                    success: function(data) {
                        var printing = $('#printing').val();
                        if (data['status']) {
                            if (printing == 1) {
                                window.myAPI.sendDataToMainProcess([data['cashier_printer'],data['kitchen_printer'],data['link'],data['cashier_printer_copies'],data['kitchen_printer_copies']]);
                            } else {
                                showFrontendAlert('success', 'تم أضافة الطلب بنجاح', '');
                            }
                        } else {
                            showFrontendAlert('error', data['message'], '');
                        }
                    },
                    error: function() {
                        showFrontendAlert('error', 'حدث خطأ', '');
                    }
                });
            })

            function category_collapse(selected_id) {
                $.each($(".multi-collapse"), function() {
                    var id = $(this).data('id');
                    if (id != selected_id) {
                        $(this).removeClass('show');
                    } else {
                        $(this).addClass('show');
                    }
                });
            }

            function go_full_screen() {
                var elem = document.documentElement;
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                }
            }

            $('.add-product').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.cashier-modes.add_product') }}',
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#div-table-receipt').css('display', 'block');
                        $('#table-receipt tbody').append(data);
                        calculate_total_cost();
                    }
                });
            })

            function removeTr(id) {
                $('#receipt-product-' + id).fadeOut(300, function() {
                    $('#receipt-product-' + id).remove();
                    calculate_total_cost();
                });
            }

            function change_quantity(item, id, price) {
                console.log('changed');
                $('#receipt-product-cost-' + id).html(item.value * price);
                calculate_total_cost();
            }

            function calculate_total_cost() {
                var total = 0;
                $.each($("#div-table-receipt tbody .receipt-product-cost"), function() {
                    total += parseFloat($(this).text());
                });
                $('#total_cost').text(total);
                if ($('#paid_up').val() > 0) {
                    rest_of_the_amount();
                }
            }

            function rest_of_the_amount() {
                var rest_of_the_amount = $('#paid_up').val() - parseFloat($('#total_cost').text());
                $('#rest_of_the_amount').html(rest_of_the_amount);
            }
            $("body").on("submit", "form", function() {
                $(this).submit(function() {
                    return false;
                });
                return true;
            }); // prevent submitting multiple times


            function qr_code_modal(status, type) {
                if (status) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('admin.cashier-modes.qr_scanner') }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            type: type
                        },
                        success: function(data) {
                            $('#submit-button').css('visibility', 'hidden');
                            $('#qr_user_id').val(null);
                            $('#paid_up').val(0);
                            rest_of_the_amount()
                            $('#QRModal').modal('show');
                            $('#QRModal .modal-body').html(null);
                            $('#QRModal .modal-body').html(data);
                        }
                    });
                } else {
                    $('#submit-button').css('visibility', 'visible');
                    $('#paid_up').val(null);
                    $('#qr_user_id').val(null);
                    rest_of_the_amount()
                }
            }





            function submit_pay_form(isStoreForm) {
                if (isStoreForm) {
                    $('#store_form').submit();
                } else {
                    $('#update_form').submit();
                }
            }
        </script>
        @yield('scripts')
    </body>

    </html>
