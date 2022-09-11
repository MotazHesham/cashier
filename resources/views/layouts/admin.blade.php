<!DOCTYPE html>
<html @if(app()->getLocale() == 'ar') dir="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/coreui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/perfect-scrollbar.min.css') }}">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    @if(app()->getLocale() == 'ar')
        <style>
            .c-sidebar-nav .c-sidebar-nav-dropdown-items{
            padding-right: 8%;
            }
        </style>
    @else
        <style>
            .c-sidebar-nav .c-sidebar-nav-dropdown-items{
            padding-left: 8%;
            }
        </style>
    @endif
    @yield('styles')
</head>

<body class="c-app">
    @include('partials.menu')
    <div class="c-wrapper">
        <header class="c-header c-header-fixed px-3">
            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <a class="c-header-brand d-lg-none" href="#">{{ trans('panel.site_title') }}</a>

            <button class="c-header-toggler mfs-3 d-md-down-none" type="button" responsive="true">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <ul class="c-header-nav @if(app()->getLocale() == 'ar') mr-auto @else ml-auto @endif">
                @if(count(config('panel.available_languages', [])) > 1)
                    <li class="c-header-nav-item dropdown d-md-down-none">
                        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                <a class="dropdown-item" href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                            @endforeach
                        </div>
                    </li>
                @endif

                <ul class="c-header-nav ml-auto">
                    <li class="c-header-nav-item dropdown notifications-menu">
                        <a href="#" class="c-header-nav-link" data-toggle="dropdown">
                            <i class="far fa-bell"></i>
                            @php($alertsCount = \Auth::user()->userUserAlerts()->where('read', false)->count())
                                @if($alertsCount > 0)
                                    <span class="badge badge-warning navbar-badge">
                                        {{ $alertsCount }}
                                    </span>
                                @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            @if(count($alerts = \Auth::user()->userUserAlerts()->withPivot('read')->limit(10)->orderBy('created_at', 'ASC')->get()->reverse()) > 0)
                                @foreach($alerts as $alert)
                                    <div class="dropdown-item">
                                        <a href="{{ $alert->alert_link ? $alert->alert_link : "#" }}" target="_blank" rel="noopener noreferrer">
                                            @if($alert->pivot->read === 0) <strong> @endif
                                                {{ $alert->alert_text }}
                                                @if($alert->pivot->read === 0) </strong> @endif
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center">
                                    {{ trans('global.no_alerts') }}
                                </div>
                            @endif
                        </div>
                    </li>
                </ul>

            </ul>
        </header>

        <div class="c-body">
            <main class="c-main">


                <div class="container-fluid">
                    @if(session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                </div>


            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>

    @include('sweetalert::alert')

    <script src="{{ asset('dashboard_offline/js/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/coreui.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/ckeditor.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/moment.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/sweetalert2.min.css') }}">
    <script src="{{ asset('dashboard_offline/js/sweetalert2.all.min.js') }}"></script>

    {{-- Sweet alert delete --}}
    <script>

        function showFrontendAlert(type, title, message){
            swal({
                title: title,
                text: message,
                type: type,
                showConfirmButton: 'Okay',
                timer: 3000
            });
        }

        function deleteConfirmation(route, div = null, partials = false) {
            swal({
                title: "{{trans('global.flash.delete_')}}",
                text: "{{trans('global.flash.sure_')}}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "{{trans('global.flash.yes_')}}",
                cancelButtonText: "{{trans('global.flash.no_')}}",
                reverseButtons: !0
            }).then(function (e) {

                if (e.value === true) {

                    $.ajax({
                        type: 'DELETE',
                        url: route,
                        data: { _token: '{{ csrf_token() }}', partials: partials},
                        success: function (results) {
                            if(div != null){
                            showFrontendAlert('success', '{{trans('global.flash.deleted')}}', '');
                            $(div).html(null);
                            $(div).html(results);
                            }else{
                            location.reload();
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>

    {{-- attributes script --}}
    <script>
        $('#attributes').on('change', function() {
            $('#attribute_options').html(null);
            $.each($("#attributes option:selected"), function(){
                //console.log($(this).val());
                add_more_attribute_options($(this).val(), $(this).text());
            });
        });


        function add_more_attribute_options(i, name){
            var select = '<div class="row">';
            select    +=    '<div class="col-md-4">';
            select    +=        '<input type="hidden" name="attribute_num[]" value="'+i+'">';
            select    +=        '<input type="text" class="form-control" name="attribute[]" value="'+name+'" placeholder="Attribute Title" readonly>';
            select    +=    '</div>';
            select    +=    '<div class="col-md-8">';
            select    +=        '<input type="text" class="form-control" name="attributes_options_'+i+'[]" placeholder="Enter attribute values" data-role="tagsinput" onchange="update_attribute_combination()">';
            select    +=    '</div>';
            select    += '</div>';

            $('#attribute_options').append(select);

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function update_attribute_combination(){
            $.ajax({
                type:"POST",
                url:'{{ route('admin.products.attribute_combination') }}',
                data:$('#product-form').serialize(),
                success: function(data){
                    $('#attribute_combination').html(data);
                }
            });
        }

    </script>

    {{-- datatables --}}
    <script>

        $(function() {
            let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
            let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
            let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
            let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
            let printButtonTrans = '{{ trans('global.datatables.print') }}'
            let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
            let selectAllButtonTrans = '{{ trans('global.select_all') }}'
            let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

            let languages = {
                'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json',
                    'ar': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json'
            };

            $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                style:    'multi+shift',
                selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [
                {
                    extend: 'selectAll',
                    className: 'btn-primary',
                    text: selectAllButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    },
                    action: function(e, dt) {
                    e.preventDefault()
                    dt.rows().deselect();
                    dt.rows({ search: 'applied' }).select();
                    }
                },
                {
                    extend: 'selectNone',
                    className: 'btn-primary',
                    text: selectNoneButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'copy',
                    className: 'btn-default',
                    text: copyButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn-default',
                    text: csvButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn-default',
                    text: excelButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn-default',
                    text: pdfButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn-default',
                    text: printButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    className: 'btn-default',
                    text: colvisButtonTrans,
                    exportOptions: {
                    columns: ':visible'
                    }
                }
                ]
            });

            $.fn.dataTable.ext.classes.sPageButton = '';
        });

    </script>

    {{-- notification --}}
    <script>
        $(document).ready(function () {
            $(".notifications-menu").on('click', function () {
                if (!$(this).hasClass('open')) {
                    $('.notifications-menu .label-warning').hide();
                    $.get('/admin/user-alerts/read');
                }
            });
        });

    </script>

    {{-- globalSearch --}}
    <script>

        $(document).ready(function() {
            $('.searchable-field').select2({
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route("admin.globalSearch") }}',
                    dataType: 'json',
                    type: 'GET',
                    delay: 200,
                    data: function (term) {
                        return {
                            search: term
                        };
                    },
                    results: function (data) {
                        return {
                            data
                        };
                    }
                },
                escapeMarkup: function (markup) { return markup; },
                templateResult: formatItem,
                templateSelection: formatItemSelection,
                placeholder : '{{ trans('global.search') }}...',
                language: {
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        var translation = '{{ trans('global.search_input_too_short') }}';

                        return translation.replace(':count', remainingChars);
                    },
                    errorLoading: function() {
                        return '{{ trans('global.results_could_not_be_loaded') }}';
                    },
                    searching: function() {
                        return '{{ trans('global.searching') }}';
                    },
                    noResults: function() {
                        return '{{ trans('global.no_results') }}';
                    },
                }

            });
            function formatItem (item) {
                if (item.loading) {
                    return '{{ trans('global.searching') }}...';
                }
                var markup = "<div class='searchable-link' href='" + item.url + "'>";
                markup += "<div class='searchable-title'>" + item.model + "</div>";
                $.each(item.fields, function(key, field) {
                    markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " + item[field] + "</div>";
                });
                markup += "</div>";

                return markup;
            }

            function formatItemSelection (item) {
                if (!item.model) {
                    return '{{ trans('global.search') }}...';
                }
                return item.model;
            }
            $(document).delegate('.searchable-link', 'click', function() {
                var url = $(this).attr('href');
                window.location = url;
            });
        });

    </script>

    @yield('scripts')
</body>

</html>
