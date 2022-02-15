@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.cashierMode.title') }}
    </div> 

    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
                <div class="row"> 
                    @foreach($categories as $category)
                        <div class="col-md-4">
                            <h4>{{ $category->name }}</h4>
                            <hr>
                            @foreach($category->products as $product)
                                <form method="post" class="add-product"> 
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">   
                                    @if($product->photo) 
                                        <img src="{{ $product->photo->getUrl('preview') }}" alt="">
                                    @endif
                                    {{ $product->name}}
                                    <input type="number" min="1" step="1" name="quantity" class="form-control" required>
                                    <hr>
                                    <div class="row"> 
                                        @foreach (json_decode($product->attributes_options) as $key => $attribute_option)
                                            @php
                                                $attribute = \App\Models\Attribute::find($attribute_option->attribute_id);
                                            @endphp
                                            <div class="col-md-6">
                                                <h5>{{ $attribute->attribute ?? '' }}</h5>
                                                <hr>
                                                <select @if($attribute->type == 'multiple') class="select2 form-control" multiple @endif class="form-control" name="attributes[]">
                                                    @foreach($attribute_option->values as $value)
                                                        <option id="{{ $value }}">{{ $value }}</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                
                                    <button class="btn btn-danger" type="submit">
                                        {{ trans('global.save') }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    @endforeach 
                </div>
            </div>
            <div class="col-md-3">
                <form action="{{ route('admin.cashier-modes.store') }}" method="Post">
                    @csrf
                    <div id="receipt-container">
    
                    </div>
                    <input type="number" name="paid_up" min="0" class="form-control">
                    <select name="voucher_code_id" id="" class="form-control">
                        @foreach($vouchercodes as $vouchercode)
                            <option value="{{ $vouchercode->id }}">{{ $vouchercode->code }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 


@endsection

@section('scripts')
@parent
    <script>
        $('.add-product').on('submit',function(event){
            event.preventDefault(); 
            $.ajax({
                type:"POST",
                url:'{{ route('admin.cashier-modes.add_product') }}',
                data:$(this).serialize(),
                success: function(data){
                    $('#receipt-container').append(data); 
                }
            });
        })

        function removeDiv(elem){ 
            $(elem).fadeOut(300, function() {
                $(elem).parent('div').remove();
            });
        }
    </script>
@endsection