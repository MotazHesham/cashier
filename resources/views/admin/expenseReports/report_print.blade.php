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
    <div style="page-break-after: always;" class="text-center">
        @php 
            $setting = \App\Models\GeneralSetting::first();
        @endphp
        <div class="text-center">
            <img class="text-center" src="{{ $setting->logo ? $setting->logo->getUrl('thumb') : ''}}" alt="">
            <h3 class="text-center mb-3">{{ $setting->website_title ?? ''}}</h3> 
        </div>
        <div> 
            <div style="padding:0 12px">
                Date:
                <b style="font-size:12px">
                    @if($start_date)
                        {{$start_date}} to {{$end_date}}
                    @else 
                        {{$year}} - {{$month}}
                    @endif
                </b>
            </div> 
            <div style="padding:0 12px">
                Print Time: 
                <b style="font-size:12px">{{ date('Y-m-d h:i a',strtotime('now')) }}</b>
            </div> 
        </div>
        
        <table id="table-receipt" class="table table-bordered table-striped text-center" style="direction: rtl;">
            <thead>
                <tr>
                    <td>المنتج</td> 
                    <td>الأضافات</td>
                    <td>الكمية</td>
                    <td>الأجمالي</td>
                </tr>
            </thead>
            <tbody> 
                @php
                    $total = 0;
                @endphp
                @foreach($products_report as $item) 
                    @php
                        $total += $item->total_cost;
                    @endphp
                    <tr>
                        <td>
                            {{$item->product_name}}   
                        </td> 
                        <td>
                            <div style="display: flex;flex-direction:row;justify-content: center;">
                                @foreach(json_decode($item->attributes) as $attribute)
                                    <small style="background: black; color: white;border-radius: 2px; padding: 2px;display:inline">{{ $attribute->price }}  {{ $attribute->variant}} </small> 
                                    @if(!$loop->last) - @endif
                                @endforeach
                            </div>
                        </td>
                        <td>
                            {{$item->quantity}}
                        </td>
                        <td>
                            {{$item->total_cost}} 
                        </td> 
                    </tr>   
                @endforeach
            </tbody>
        </table>
        <div style="padding: 0 10px">
            <b>{{ number_format($total,2) }} LE</b> :الأجمالي  
            <br>
            <b>{{ number_format(($total - $ordersTotal),2) }} LE</b> :الخصومات  
            <br>
            <b>{{ number_format($ordersTotal,2) }} LE</b> :الأجمالي بعد الخصم  
        </div> 
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            window.print(); 
        });
    </script>
</body>

</html>
