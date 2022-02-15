@if (count($combinations[0]) > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="text-center">
                    <label for="" class="control-label">السمة</label>
                </td>
                <td class="text-center">
                    <label for="" class="control-label">السعر الأضافي</label>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($combinations as $key => $combination) 
                @foreach ($combination as $key => $item) 
                    <tr>
                        <td>
                            <label for="" class="control-label">{{ $item }}</label>
                        </td>
                        <td>
                            @php
                            if($product){
                                $attributeProduct = $product->attributeProduct()->where('variant',$item)->first();
                                if($attributeProduct){
                                    $extra_price = $attributeProduct->price;
                                }else{
                                    $extra_price = 0;
                                }
                            }else{
                                $extra_price = 0;  
                            }
                            @endphp
                            <input type="number" name="extra_price_{{ $item }}" value="{{ $extra_price }}"  min="0" step="0.1" class="form-control" required>
                        </td> 
                    </tr> 
                @endforeach
            @endforeach
        </tbody>
    </table>
@endif
