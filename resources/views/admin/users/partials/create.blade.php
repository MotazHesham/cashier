<div class="form-group">
    <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name"
        value="{{ old('name', '') }}" required>
    @if ($errors->has('name'))
        <div class="invalid-feedback">
            {{ $errors->first('name') }}
        </div>
    @endif
    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
</div>
<div class="form-group">
    <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email"
        id="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
        <div class="invalid-feedback">
            {{ $errors->first('email') }}
        </div>
    @endif
    <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
</div>
<div class="form-group">
    <label class="required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
    <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone"
        id="phone" value="{{ old('phone', '') }}" required>
    @if ($errors->has('phone'))
        <div class="invalid-feedback">
            {{ $errors->first('phone') }}
        </div>
    @endif
    <span class="help-block">{{ trans('cruds.user.fields.phone_helper') }}</span>
</div>
<div class="form-group">
    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password"
        id="password" required>
    @if ($errors->has('password'))
        <div class="invalid-feedback">
            {{ $errors->first('password') }}
        </div>
    @endif
    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
</div>
<div class="form-group">
    <label for="identity">{{ trans('cruds.user.fields.identity') }}</label>
    <div class="needsclick dropzone {{ $errors->has('identity') ? 'is-invalid' : '' }}" id="identity-dropzone">
    </div>
    @if ($errors->has('identity'))
        <div class="invalid-feedback">
            {{ $errors->first('identity') }}
        </div>
    @endif
    <span class="help-block">{{ trans('cruds.user.fields.identity_helper') }}</span>
</div>

@section('scripts')
    <script>
        var uploadedIdentityMap = {}
        Dropzone.options.identityDropzone = {
            url: '{{ route('admin.users.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="identity[]" value="' + response.name + '">')
                uploadedIdentityMap[file.name] = response.name
            },
            removedfile: function(file) {
                console.log(file)
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedIdentityMap[file.name]
                }
                $('form').find('input[name="identity[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($user) && $user->identity)
                    var files = {!! json_encode($user->identity) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="identity[]" value="' + file.file_name + '">')
                    }
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
