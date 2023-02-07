
<div class="partials-scrollable">
    <div class="row">
        @forelse ($attributes as $key => $attribute_option)
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
                            name="attributes[{{$attribute_option->attribute_id}}][]" value="{{ $value }}"
                            id="{{ $product->id . $value }}">
                            <label
                                for="{{ $product->id . $value }}">{{ $value }}</label>
                        @endforeach
                    </span>
                </div>
            </div>
        @empty
          <div class="">
            لا يتوافر لدينا أضافات لهذا المنتج حاليا
          </div>
        @endforelse
    </div>
</div>
