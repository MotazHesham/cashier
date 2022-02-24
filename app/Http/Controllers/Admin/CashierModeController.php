<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\VoucherCode;
use App\Models\AttributeProduct; 
use Session;
use Carbon\Carbon;
use Alert;
use DB;

class CashierModeController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('cashier_mode_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $now_date = date('Y-m-d',strtotime('now'));  

        $categories = ProductCategory::with('products.attributeProduct')->get();
        $vouchercodes = VoucherCode::where('start_date','<=',$now_date)->where('end_date','>=',$now_date)->get();
        Session::put('counter', 0);

        return view('admin.cashierModes.add',compact('categories','vouchercodes'));
    } 

    public function edit(Request $request)
    { 
        $order = Order::where('code',$request->code)->first();

        if(!$order){
            Alert::warning('Order Not Found');
            return redirect()->route('admin.cashier-modes.index');
        }

        $order->load('products.product'); 
        $now_date = date('Y-m-d',strtotime('now'));  

        $categories = ProductCategory::with('products.attributeProduct')->get();
        $vouchercodes = VoucherCode::where('start_date','<=',$now_date)->where('end_date','>=',$now_date)->get();

        Session::put('counter', 0);


        $isAdmin = auth()->user()->roles->contains(1);

        if (!$isAdmin) {
            $created_at = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $order->created_at)->format('Y-m-d H:i:s');
            if(Carbon::parse($created_at)->addMinutes(10)->isPast()){ 
                Alert::warning('لم يتم تنفيذ الأمر', 'تعدي الوقت المسموح به للتعديل برجاء التواصل مع الأدمن لتنفيذ الأمر '); 
                return redirect()->route('admin.cashier-modes.index');
            }
        }

        return view('admin.cashierModes.edit',compact('order','categories','vouchercodes'));
    }
    public function add_product(Request $request){
        Session::put('counter', Session::get('counter') + 1); 

        $product = Product::findOrFail($request->product_id);

        $attributes = $request->input('attributes', []);
        $quantity = $request->quantity;
        $extra_price = 0;

        foreach($attributes as $value){
            $attributeProduct = AttributeProduct::where('product_id',$product->id)->where('variant',$value)->first(); 
            $extra_price += $attributeProduct->price;
        }

        $product_cost_with_extra = $extra_price + $product->price;
        return view('admin.cashierModes.partials.add_product',compact('product','attributes','quantity','product_cost_with_extra'));
    }
    public function store(Request $request){   
        try{
            DB::beginTransaction();
            // generate Order Code
                $now_date = date('Ymd',strtotime('now'));
                $order = Order::latest()->first(); 
                if($order){
                    $exploded_code = explode('-',$order->code);
                    if($now_date == $exploded_code[0]){
                        $code = $exploded_code[0] . '-' . ($exploded_code[1] + 1);
                    }else{
                        $code = $now_date . '-' . '1';
                    }
                }else{
                    $code = $now_date . '-' . '1';
                }
            // ----------------------

            if(!$request->has('products')){
                Alert::error('حدث خطأ','من فضلك اختر منتج أولا');
                return redirect()->route('admin.cashier-modes.index');
            }
            $order = Order::create([
                'code' => $code,
                'entry_date' => date('Y-m-d',strtotime('now')),
                'paid_up' => $request->paid_up, 
                'total_cost' => 0,
                'voucher_code_id' => $request->voucher_code_id,
            ]);
            
            $order_total_cost = 0;
            
            foreach($request->products as $key => $selected_product){
                $product = Product::find($selected_product['product_id']);

                $attributes = array();
                $extra_price = 0;
                $total_cost = $product->price * $selected_product['quantity'];
                if(isset($selected_product['attributes'])){
                    foreach($selected_product['attributes'] as $value){
                        $item = array();
                        $attributeProduct = AttributeProduct::where('product_id',$selected_product['product_id'])->where('variant',$value)->first(); 
                        if($attributeProduct){
                            $item['attribute_id'] = $attributeProduct->attribute_id; 
                            $item['variant'] = $value; 
                            $item['price'] = $attributeProduct->price; 
                            array_push($attributes, $item); 
                            $extra_price += ($attributeProduct->price * $selected_product['quantity']);
                        }
                    }
                }
                
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $selected_product['product_id'],
                    'product_name' => $product->name,
                    'attributes' => json_encode($attributes),
                    'quantity' => $selected_product['quantity'],
                    'price' => $product->price,
                    'extra_price' => $extra_price,
                    'total_cost' => $total_cost + $extra_price,
                ]); 
                
                $order_total_cost += ($total_cost + $extra_price);
            }
            $voucher_code = VoucherCode::find($request->voucher_code_id);
            $discount = $voucher_code->discount ?? 0;
            if($request->voucher_code_id != null && $voucher_code && $discount != 0){
                if($voucher_code->type == 'percentage'){
                    $discount = $order_total_cost * ($discount /100);
                    $order->total_cost = $order_total_cost - $discount;
                }else{
                    $order->total_cost = $order_total_cost - $discount;
                }
                $order->discount = $discount;
            }else{
                $order->total_cost = $order_total_cost;
            }
            $order->save(); 

            $order->load('products.product');
            DB::commit();
            return route('admin.orders.print',$order->id);
        }catch(\Exception $ex){
            DB::rollBack();
            return 0;
        }
    }

    public function update(Request $request){   
        try{
            DB::beginTransaction();
            $order = Order::findOrFail($request->order_id);
            $order->load('products.product');

            foreach($order->products as $order_product){
                $order_product->delete();
            }

            $order_total_cost = 0;
            
            foreach($request->products as $key => $selected_product){
                $product = Product::find($selected_product['product_id']);

                $attributes = array();
                $extra_price = 0;
                $total_cost = $product->price * $selected_product['quantity'];
                if(isset($selected_product['attributes'])){
                    foreach($selected_product['attributes'] as $value){
                        $item = array();
                        $attributeProduct = AttributeProduct::where('product_id',$selected_product['product_id'])->where('variant',$value)->first(); 
                        if($attributeProduct){
                            $item['attribute_id'] = $attributeProduct->attribute_id; 
                            $item['variant'] = $value; 
                            $item['price'] = $attributeProduct->price; 
                            array_push($attributes, $item); 
                            $extra_price += ($attributeProduct->price * $selected_product['quantity']);
                        }
                    }
                }
                
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $selected_product['product_id'],
                    'product_name' => $product->name,
                    'attributes' => json_encode($attributes),
                    'quantity' => $selected_product['quantity'],
                    'price' => $product->price,
                    'extra_price' => $extra_price,
                    'total_cost' => $total_cost + $extra_price,
                ]); 
                
                $order_total_cost += ($total_cost + $extra_price);
            }

            $voucher_code = VoucherCode::find($request->voucher_code_id);
            $discount = $voucher_code->discount ?? 0;
            if($request->voucher_code_id != null && $voucher_code && $discount != 0){
                if($voucher_code->type == 'percentage'){
                    $discount = $order_total_cost * ($discount /100);
                    $order->total_cost = $order_total_cost - $discount;
                }else{
                    $order->total_cost = $order_total_cost - $discount;
                }
                $order->discount = $discount;
            }else{
                $order->total_cost = $order_total_cost;
            }
            $order->save(); 

            $order->update([ 
                'paid_up' => $request->paid_up,
                'discount' => $discount, 
                'voucher_code_id' => $request->voucher_code_id,
            ]);

            $order->load('products.product');
            
            DB::commit();
            return route('admin.orders.print',$order->id); 
        }catch(\Exception $ex){
            DB::rollBack();
            return 0;
        }
    }

}
