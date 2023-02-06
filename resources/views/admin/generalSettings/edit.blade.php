@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.generalSetting.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.general-settings.update', [$generalSetting->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="website_title">{{ trans('cruds.generalSetting.fields.website_title') }}</label>
                    <input class="form-control {{ $errors->has('website_title') ? 'is-invalid' : '' }}" type="text"
                        name="website_title" id="website_title"
                        value="{{ old('website_title', $generalSetting->website_title) }}">
                    @if ($errors->has('website_title'))
                        <div class="invalid-feedback">
                            {{ $errors->first('website_title') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.generalSetting.fields.website_title_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="phone_1">{{ trans('cruds.generalSetting.fields.phone_1') }}</label>
                    <input class="form-control {{ $errors->has('phone_1') ? 'is-invalid' : '' }}" type="text"
                        name="phone_1" id="phone_1" value="{{ old('phone_1', $generalSetting->phone_1) }}">
                    @if ($errors->has('phone_1'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_1') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.generalSetting.fields.phone_1_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="phone_2">{{ trans('cruds.generalSetting.fields.phone_2') }}</label>
                    <input class="form-control {{ $errors->has('phone_2') ? 'is-invalid' : '' }}" type="text"
                        name="phone_2" id="phone_2" value="{{ old('phone_2', $generalSetting->phone_2) }}">
                    @if ($errors->has('phone_2'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_2') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.generalSetting.fields.phone_2_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="address">{{ trans('cruds.generalSetting.fields.address') }}</label>
                    <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text"
                        name="address" id="address" value="{{ old('address', $generalSetting->address) }}">
                    @if ($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.generalSetting.fields.address_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="logo">{{ trans('cruds.generalSetting.fields.logo') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('logo') ? 'is-invalid' : '' }}" id="logo-dropzone">
                    </div>
                    @if ($errors->has('logo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('logo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.generalSetting.fields.logo_helper') }}</span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="menu_qr">{{ trans('cruds.generalSetting.fields.menu_qr') }}</label>
                            <div class="needsclick dropzone {{ $errors->has('menu_qr') ? 'is-invalid' : '' }}" id="menu_qr-dropzone">
                            </div>
                            @if ($errors->has('menu_qr'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('menu_qr') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.generalSetting.fields.menu_qr_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{ $generalSetting->menu_qr ? QrCode::size(200)->generate(url($generalSetting->menu_qr->getUrl())) : ''}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.generalSetting.fields.cashier_printer') }} <span
                                    class="badge badge-success">{{ $cashier_printer->printer }}</span></label>
                            <select class="form-control {{ $errors->has('cashier_printer') ? 'is-invalid' : '' }}"
                                name="cashier_printer" id="cashier_printer" required>
                                <option value disabled {{ old('cashier_printer', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                            </select>
                            @if ($errors->has('cashier_printer'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cashier_printer') }}
                                </div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.generalSetting.fields.cashier_printer_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">{{ trans('cruds.generalSetting.fields.print_times') }}</label>
                            <input class="form-control {{ $errors->has('print_times_cashier') ? 'is-invalid' : '' }}"
                                type="number" min="1" step="1" max="5" name="print_times_cashier"
                                id="print_times_cashier"
                                value="{{ old('print_times_cashier', $cashier_printer->print_times) }}">
                            @if ($errors->has('print_times_cashier'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('print_times_cashier') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.generalSetting.fields.print_times_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.generalSetting.fields.kitchen_printer') }} <span
                                    class="badge badge-success">{{ $kitchen_printer->printer }}</span></label>
                            <select class="form-control {{ $errors->has('kitchen_printer') ? 'is-invalid' : '' }}"
                                name="kitchen_printer" id="kitchen_printer" required>
                                <option value disabled {{ old('kitchen_printer', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                            </select>
                            @if ($errors->has('kitchen_printer'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('kitchen_printer') }}
                                </div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.generalSetting.fields.kitchen_printer_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">{{ trans('cruds.generalSetting.fields.print_times') }}</label>
                            <input class="form-control {{ $errors->has('print_times_kitchen') ? 'is-invalid' : '' }}"
                                type="number" min="1" step="1" max="5" name="print_times_kitchen"
                                id="print_times_kitchen"
                                value="{{ old('print_times_kitchen', $kitchen_printer->print_times) }}">
                            @if ($errors->has('print_times_kitchen'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('print_times_kitchen') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.generalSetting.fields.print_times_helper') }}</span>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        JSPM.JSPrintManager.auto_reconnect = true;
        JSPM.JSPrintManager.start();
        JSPM.JSPrintManager.WS.onStatusChanged = function() {
            if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open) {
                JSPM.JSPrintManager.getPrintersInfo().then(function(printersList) {
                    console.log(printersList);
                    for (i = 0; i < printersList.length; i++) {
                        $('#kitchen_printer').append('<option id=' + printersList[i]['name'] + '>' +
                            printersList[i]['name'] + '</option>');
                        $('#cashier_printer').append('<option id=' + printersList[i]['name'] + '>' +
                            printersList[i]['name'] + '</option>');
                    }
                });
            }
        };
    </script>
    <script>
        Dropzone.options.logoDropzone = {
            url: '{{ route('admin.general-settings.storeMedia') }}',
            maxFilesize: 6, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 6,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').find('input[name="logo"]').remove()
                $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="logo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($generalSetting) && $generalSetting->logo)
                    var file = {!! json_encode($generalSetting->logo) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        Dropzone.options.menuQrDropzone = {
            url: '{{ route('admin.general-settings.storeMedia') }}',
            maxFilesize: 6, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 6,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').find('input[name="menu_qr"]').remove()
                $('form').append('<input type="hidden" name="menu_qr" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="menu_qr"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($generalSetting) && $generalSetting->menu_qr)
                    var file = {!! json_encode($generalSetting->menu_qr) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="menu_qr" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
