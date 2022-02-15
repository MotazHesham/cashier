@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', [$product->id]) }}"
                enctype="multipart/form-data" id="product-form">
                @method('PUT')
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" id="">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                            id="name" value="{{ old('name', $product->name) }}" required>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="price">{{ trans('cruds.product.fields.price') }}</label>
                        <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number"
                            name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                        @if ($errors->has('price'))
                            <div class="invalid-feedback">
                                {{ $errors->first('price') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.price_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="category_id">{{ trans('cruds.product.fields.category') }}</label>
                        <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}"
                            name="category_id" id="category_id" required>
                            @foreach ($categories as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ (old('category_id') ? old('category_id') : $product->category->id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.category_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="required" for="photo">{{ trans('cruds.product.fields.photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone">
                        </div>
                        @if ($errors->has('photo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('photo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.photo_helper') }}</span>
                    </div> 
                    <div class="form-group col-md-6">
                        <label for="description">{{ trans('cruds.product.fields.description') }}</label>
                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                            id="description">{{ old('description', $product->description) }}</textarea>
                        @if ($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="attributes">{{ trans('cruds.product.fields.attributes') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all"
                                style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all"
                                style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('attributes') ? 'is-invalid' : '' }}"
                            name="attributes[]" id="attributes" multiple>
                            @foreach ($attributes as $id => $attribute)
                                <option value="{{ $id }}"
                                    {{ in_array($id, old('attributes', [])) || in_array($id,json_decode($product->attributes))? 'selected' : '' }}>
                                    {{ $attribute }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('attributes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('attributes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.attributes_helper') }}</span>
                        
                        <div class="attribute_options mt-4" id="attribute_options">
                            {{-- ajax call --}}  
                            
                            @foreach (json_decode($product->attributes_options) as $key => $attribute_option)
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="hidden" name="attribute_num[]" value="{{ $attribute_option->attribute_id }}">
                                        <input type="text" class="form-control" name="attribute[]" value="{{ \App\Models\Attribute::find($attribute_option->attribute_id)->attribute ?? '' }}" placeholder="Attribute Title" disabled>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="attributes_options_{{ $attribute_option->attribute_id }}[]" placeholder="Enter attribute values" value="{{ implode(',', $attribute_option->values) }}" data-role="tagsinput" onchange="update_attribute_combination()">
                                    </div> 
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-3"> 
                        <div class="attribute_combination" id="attribute_combination">

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
    <script>
        $(document).ready(function(){
            update_attribute_combination();
        }); 

        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
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
                $('form').find('input[name="photo"]').remove()
                $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="photo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($product) && $product->photo)
                    var file = {!! json_encode($product->photo) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
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
