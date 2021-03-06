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

<body> 
    <div style="page-break-after: always;">
        @php
            $date = explode(' ',$order->created_at,2);
            $code = explode('-',$order->code);
            $setting = \App\Models\GeneralSetting::first();
        @endphp
        <div class="text-center">
            <img class="text-center" src="{{ $setting->logo ? $setting->logo->getUrl('preview') : ''}}" alt="">
            <h3 class="text-center mb-3">{{ $setting->website_title ?? ''}}</h3>
            <small> Order: <b>{{ $code[1] }}</b> </small>
        </div>
        <div style="display: flex;justify-content:center;border:1px dotted black;border-top:hidden"> 
            <div style="padding:0 12px">Date: <b style="font-size:12px">{{ $date[0] ?? ''}} {{ $date[1] ?? ''}}</b></div> 
        </div>
        
        <table id="table-receipt" class="table table-bordered table-striped text-center" style="direction: rtl;">
            <thead>
                <tr>
                    <td>المنتج</td>
                    <td>السعر</td>
                    <td>الكمية</td>
                    <td>الأجمالي</td>
                </tr>
            </thead>
            <tbody> 
                @foreach($order->products as $order_product)
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
                    <tr>
                        <td>
                            <div style="display: flex;flex-direction:column">
                                <div>
                                    {{$order_product->product->name ?? ''}}   
                                </div>
                            </div>
                        </td>
                        <td> 
                            <div style="display: flex;flex-direction:column">
                                <div>
                                    @php
                                        $total = $order_product->price;
                                        $extra_single = 0;
                                        foreach($single as $row){
                                            $extra_single += $row['price'];
                                        }

                                    @endphp 
                                    {{ $total + $extra_single}}LE
                                </div>
                                <div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$order_product->quantity}}
                        </td>
                        <td>
                            {{$order_product->total_cost}} 
                        </td> 
                    </tr>  
                    <tr style="border-width: 0 1px;  border-color: inherit; border-style: solid;font-size:14px;font-weight: 900;">
                        <td colspan="4">
                            @foreach($single as $row)
                                <span style="background: black; color: white;border-radius: 2px; padding: 2px;">{{ $row['slug'] }} <small>{{ $row['variant']}}</small></span> -
                            @endforeach
                            
                            @foreach($multiple as $row)    
                                <span style="background: black; color: white;border-radius: 2px; padding: 2px;">{{ $row['slug'] }} <small>{{ $row['variant']}}</small> <small>+{{ $row['price'] }}</small></span> -
                            @endforeach 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 0 10px">
            <span>{{ $order->total_cost }} LE</span> :الأجمالي 
            <br>
            @if($order->discount) 
                <span>{{ $order->discount }} LE</span> :الخصم   
                <br>
            @endif
            <span>{{ $order->paid_up }} LE</span> :المدفوع 
            <br>
            <span>{{ $order->paid_up - $order->total_cost}} LE</span> :المتبقي  
        </div> 
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            window.print();
            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    window.close();
                }, 100); 
            }, 100);
        });
    </script>
</body>

</html>
