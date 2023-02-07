


<tr id="receipt-product-{{ Session::get('counter') }}">
    <td>
        {{ $product->name }}
        <br>
        @foreach($attributes as $value)
            <b class="badge bg-info">{{ $value }}</b>
        @endforeach
    </td>
    <td>
        <input type="hidden" name="products[{{ Session::get('counter') }}][user_id]" value="{{$user_id}}">
        <input type="hidden" name="products[{{ Session::get('counter') }}][quantity]" value="{{$quantity}}">
        {{$quantity}}
    </td>
    <td id="receipt-product-cost-{{ Session::get('counter') }}" class="receipt-product-cost">
        {{ $product_cost_with_extra * $quantity }}
    </td>
    <td>
        <select name="products[{{ Session::get('counter') }}][attributes][]" id="" style="display: none" multiple>
            @foreach($attributes as $value)
                <option value="{{$value}}" selected>{{ $value }}</option>
            @endforeach
        </select> {{-- form purpose --}}

        <input type="hidden" name="products[{{ Session::get('counter') }}][product_id]" value="{{ $product->id }}">
        <input type="hidden" name="products[{{ Session::get('counter') }}][product_cost]" value="{{ $product_cost_with_extra }}">
        <button class="btn btn-outline-danger" type="button" onclick="removeTr({{ Session::get('counter') }})"><i class="fas fa-trash"></i></button>
    </td>
</tr>
