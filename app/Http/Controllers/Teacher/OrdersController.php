<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Student;
use App\Models\Product;
use App\Models\VoucherCode;
use App\Models\ProductCategory;
use App\Models\OrderProduct;
use App\Models\AttributeProduct;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Session;
use Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{

    public function index()
    {
        $orders = Order::where('created_by_id',auth()->id())->get();
        return view('teacher.orders.index',compact('orders'));
    }

    public function edit(Order $order)
    {
        $explode = explode('-',$order->description);
        $grade = $explode[0];
        $class = $explode[1];
        foreach(Student::GRADE_SELECT as $key => $grd){
          if($grd == $grade){
            $grade = $key;
          }
        }
        foreach(Student::CLASS_SELECT as $key => $cls){
          if($cls == $class){
            $class = $key;
          }
        }
        if($order->viewed){
            Alert::warning('لم يتم تنفيذ الأمر','تم طباعة الطلب برجاء التواصل معنا للتعديل');
            return redirect()->route('teacher.orders.index');
        }

        $order->load('voucher_code', 'products.product', 'created_by');

        $now_date = date('Y-m-d',strtotime('now'));

        $categories = ProductCategory::with('products.attributeProduct')->get();

        $students = Student::where('grade',$grade)->where('class',$class)->with('user.order_products')->get();
        Session::put('counter', 0);
        return view('teacher.orders.edit', compact('order', 'categories','students'));
    }

    public function update(Request $request){
        try{
            DB::beginTransaction();
            $order = Order::findOrFail($request->order_id);
            if($order->viewed){
                Alert::warning('لم يتم تنفيذ الأمر','تم طباعة الطلب برجاء التواصل معنا للتعديل');
                return redirect()->route('teacher.orders.index');
            }
            // check ability to edit
            if(auth()->id() != $order->created_by_id){
              Alert::error('cant edit this order');
            }
            //----------------------


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
                    'user_id' => $selected_product['user_id'],
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

            Alert::success('تم التحديث');
            DB::commit();
            return redirect()->route('teacher.orders.index');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect()->route('teacher.orders.index');
        }
    }

    public function destroy(Order $order)
    {


        if($order->viewed){
            Alert::warning('لم يتم تنفيذ الأمر','يرجي التواصل معنا لأمكانية ألغاء الطلب');
            return redirect()->route('teacher.orders.index');
        }

        $order->delete();

        Alert::success('تم بنجاح', 'تم  حذف الطلب بنجاح ');
        return 1;
    }
}
