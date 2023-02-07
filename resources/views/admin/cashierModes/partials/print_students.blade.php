<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('cashier/vendor/bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('cashier/vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('cashier/vendor/js/bootstrap.min.js') }}"></script>
    <style>

		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
        .table>:not(caption)>*>*{
            padding:0
        }
    </style>
</head>

<body style="direction:rtl">
    <div style="page-break-after: always;">
        @php
            $date = explode(' ',$order->created_at,2);
            $code = explode('-',$order->code);
            $setting = \App\Models\GeneralSetting::first();
        @endphp
        <div class="text-center">
            <img class="text-center" src="{{ $setting->logo ? $setting->logo->getUrl('preview') : ''}}" alt="">
            <h3 class="text-center mb-3">{{ $setting->website_title ?? ''}}</h3>
            <h4 class="tex-center"> {{trans('cruds.order.fields.order_from')}} : {{$order->created_by->name ?? '' }} </h4>
            <h4 class="text-center">{{ $order->order_from == 'teacher' ? $order->description : ''}}</h4>
            <small> Order: <b>{{ $code[1] }}</b> </small>
        </div>
        <div style="display: flex;justify-content:center;border:1px dotted black;border-top:hidden">
            <div style="padding:0 12px">Date: <b style="font-size:12px">{{ $date[0] ?? ''}} {{ $date[1] ?? ''}}</b></div>
        </div>


        <div class="">
          @foreach($user_products as $key => $products)
            @php
              $user = \App\Models\User::find($key);
              $total_cost = $products->sum('total_cost');
            @endphp
            {{ $user->name ?? ''}}  <span style="background: black; color: white;border-radius: 2px; padding: 2px">الأجمالي : EGP{{$total_cost}}</span>
            <br> <br>
            @foreach($products as $order_product)
              @php
                  $single = array();
                  $multiple = array();
                  foreach (json_decode($order_product->attributes) as $row){
                      $item = array();

                      $attribute = \App\Models\Attribute::find($row->attribute_id);
                      $item['slug'] = $attribute->slug ?? '';
                      $item['variant'] = $row->variant;
                      $item['price'] = $row->price;
                      if($attribute){
                          if($attribute->type == 'multiple'){
                              array_push($multiple,$item);
                          }else{
                              array_push($single,$item);
                          }
                      }else{
                          array_push($multiple,$item);
                      }
                  }
              @endphp
              <span style="background: black; color: white;border-radius: 2px; padding: 2px;margin:2px" >

                 <small>(x{{$order_product->quantity}} {{$order_product->product->name ?? ''}})</small>
                 @foreach($single as $row)
                     <span style="background: black; color: white;border-radius: 2px; padding: 2px;">{{ $row['slug'] }} <small>{{ $row['variant']}}</small></span> -
                 @endforeach

                 @foreach($multiple as $row)
                     <span style="background: black; color: white;border-radius: 2px; padding: 2px;">{{ $row['slug'] }} <small>{{ $row['variant']}}</small> <small>+{{ $row['price'] }}</small></span> -
                 @endforeach
             </span>
            @endforeach
            <hr>
          @endforeach
        </div>
  </div>
    <script type="text/javascript">
        $(document).ready(function() {
            window.print();
            setTimeout(() => {
                window.close();
            }, 100);
        });
    </script>
</body>

</html>
