<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
        table, th {
            border: 1px solid;
        }
        table tr:nth-child(even){background-color: #c8c1c1;}
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>

<body>
    <div style="text-align:center">
        @php
            $date = explode(' ',$order->created_at,2);
            $code = explode('-',$order->code);
            $setting = \App\Models\GeneralSetting::first();
        @endphp
        <div>
            <h3>{{ $setting->website_title ?? ''}}</h3>
            <h4>  By: {{$order->created_by->name ?? 'admin' }}</h4>
            <h4>{{ $order->order_from == 'teacher' ?  $order->description : ''}}</h4>
            <small> Order: <b>{{ $code[1] ?? $order->code }}</b> -> <span>{{ $order->payment_type ?? '' }}</span></small>
        </div>
        <div style="display: flex;justify-content:center;border:1px dotted black;border-top:hidden">
            <div style="padding:0 12px">Date: <b style="font-size:12px">{{ $date[0] ?? ''}} {{ $date[1] ?? ''}}</b></div>
        </div>

        <table style="margin-left: auto; margin-right: auto;margin-bottom:40px">
            <thead>
                <tr style="background-color: #04AA6D;color:white">
                    <td>Quantity</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
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
                    <tr style="padding:5px">
                        <td>
                            {{$order_product->quantity}}
                        </td>
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
                            {{$order_product->total_cost}}
                        </td>
                    </tr>
                    <tr style="border-width: 0 1px;  border-color: inherit; border-style: solid;font-size:14px;font-weight: 900;">
                        <td colspan="4">
                            @foreach($single as $row)
                                <span style="background: black; color: white;border-radius: 10px; padding: 3px;margin:5px">{{ $row['slug'] }} <small>{{ $row['variant']}}</small></span> -
                            @endforeach

                            @foreach($multiple as $row)
                                <span style="background: black; color: white;border-radius: 10px; padding: 3px;margin:5px">{{ $row['slug'] }} <small>{{ $row['variant']}}</small> <small>+{{ $row['price'] }}</small></span> -
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 0 10px">
            <span>Total: {{ $order->total_cost }} LE</span>
            <br>
            @if($order->discount > 0)
                <span>Disccount: {{ $order->discount }} LE</span>
                <br>
            @endif
            <span>Paid: {{ $order->paid_up }} LE</span>
            <br>
            <span>Remain: {{ $order->paid_up - $order->total_cost}} LE</span>
        </div>
    </div>

</body>

</html>
