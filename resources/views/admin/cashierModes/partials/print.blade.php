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
    </style>
</head>

<body> 
    <div style="page-break-after: always;">
        @php
            $date = explode(' ',$order->created_at,2);
            $code = explode('-',$order->code);
            $setting = \App\Models\GeneralSetting::first();
        @endphp
        <h3 class="text-center mb-3">{{ $setting->website_title ?? ''}}</h3>
        <div style="display: flex;justify-content:space-around;border:1px dotted black;border-top:hidden"> 
            <div>Date: <b>{{ $date[0] ?? ''}}</b></div>
            <div>Order Code: <b>{{ $code[1] }}</b></div>
            <div>Time: <b>{{ $date[1] ?? ''}}</b></div>
        </div>
        
        <table id="table-receipt" class="table table-borderless table-striped" style="direction: rtl;">
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
                                    @foreach($single as $row)
                                        <span class="badge bg-info">{{ $row['slug'] }} <small>{{ $row['variant']}}</small></span>
                                        <br>
                                    @endforeach
                                </div>
                                <div> 
                                    @foreach($multiple as $row)   
                                        <span>{{ $row['slug'] }} <small>{{ $row['variant']}}</small> <small>(+{{ $row['price'] }}LE)</small></span>
                                        <br>
                                    @endforeach
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
                            {{$order_product->total_cost}}LE 
                        </td> 
                    </tr>  
                @endforeach
            </tbody>
        </table>
        <div style="padding: 20px; border: 1px solid black; width: fit-content; border-radius: 10px;">
            <span>{{ $order->total_cost }} LE</span> :الأجمالي 
        </div>
        <div style="padding: 8px 20px;">
            <span>{{ $order->discount }} LE</span> :الخصم  
        </div>
        <div style="padding: 8px 20px;">
            <span>{{ $order->paid_up }} LE</span> :المدفوع  
        </div>
        <div style="padding: 8px 20px;">
            <span>{{ $order->paid_up - $order->total_cost}} LE</span> :المتبقي  
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            window.print();
            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    window.location.href = '{{ route('admin.cashier-modes.index') }}'; 
                }, 100); 
            }, 100);
        });
    </script>
</body>

</html>
