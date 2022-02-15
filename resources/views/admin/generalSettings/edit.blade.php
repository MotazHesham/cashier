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
@endsection
