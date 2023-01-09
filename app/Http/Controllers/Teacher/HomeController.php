<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Student;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\VoucherCode;
use Illuminate\Support\Facades\Session;
use App\Models\AttributeProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $grade = $request->grade ? $request->grade : 'pre-k';
        $class = $request->class ? $request->class : '01';
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->get()->first();

        $categories = ProductCategory::with('products.attributeProduct')->get();

        $students = Student::where('grade', $grade)->where('class', $class)->with('user')->get();
        return view('teacher.home', compact('categories', 'students', 'grade', 'class'));
    }

    public function get_products(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->get();
        return view('teacher.partials.products', compact('products'));
    }

    public function get_attributes(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $attributes = json_decode($product->attributes_options);
        return view('teacher.partials.attributes', compact('product', 'attributes'));
    }

    public function add_product(Request $request)
    {
        Session::put('counter', Session::get('counter') + 1);

        $product = Product::findOrFail($request->product_id);
        $student = Student::findOrFail($request->student_id);

        $attributes = [];
        foreach ($request->input('attributes', []) as $key => $values) {
            foreach ($values as $value) {
                $attributes[] = $value;
            }
        }
        $quantity = $request->quantity;
        $extra_price = 0;

        foreach ($attributes as $value) {
            $attributeProduct = AttributeProduct::where('product_id', $product->id)->where('variant', $value)->first();
            $extra_price += $attributeProduct->price;
        }

        $product_cost_with_extra = $extra_price + $product->price;
        $user_id = $student->user_id;
        return view('teacher.partials.add_product', compact('product', 'attributes', 'quantity', 'product_cost_with_extra', 'user_id'));
    }

    public function send_order(Request $request)
    {
        try {
            DB::beginTransaction();
            // generate Order Code
            $now_date = date('Ymd', strtotime('now'));
            $order = Order::latest()->first();
            if ($order) {
                $exploded_code = explode('-', $order->code);
                if ($now_date == $exploded_code[0]) {
                    $code = $exploded_code[0] . '-' . ($exploded_code[1] + 1);
                } else {
                    $code = $now_date . '-' . '1';
                }
            } else {
                $code = $now_date . '-' . '1';
            }
            // ----------------------

            if (!$request->has('products')) {
                Alert::error('حدث خطأ', 'من فضلك اختر منتج أولا');
                return redirect()->route('teacher.home');
            }
            $order = Order::create([
                'code' => $code,
                'entry_date' => date('Y-m-d', strtotime('now')),
                'paid_up' => 0,
                'total_cost' => 0,
                'payment_type' => 'cash',
                'order_from' => 'teacher',
                'viewed' => 0,
                'description' => $request->description
            ]);

            $order_total_cost = 0;

            foreach ($request->products as $key => $selected_product) {
                $product = Product::find($selected_product['product_id']);

                $attributes = array();
                $extra_price = 0;
                $total_cost = $product->price * $selected_product['quantity'];
                if (isset($selected_product['attributes'])) {
                    foreach ($selected_product['attributes'] as $value) {
                        $item = array();
                        $attributeProduct = AttributeProduct::where('product_id', $selected_product['product_id'])->where('variant', $value)->first();
                        if ($attributeProduct) {
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
            if ($request->voucher_code_id != null && $voucher_code && $discount != 0) {
                if ($voucher_code->type == 'percentage') {
                    $discount = $order_total_cost * ($discount / 100);
                    $order->total_cost = $order_total_cost - $discount;
                } else {
                    $order->total_cost = $order_total_cost - $discount;
                }
                $order->discount = $discount;
            } else {
                $order->total_cost = $order_total_cost;
            }



            $order->save();
            DB::commit();
            Alert::success('تم أضافة الطلب بنجاخ سيتم التأكيد في خلال 10 دقائق');
            return redirect()->route('teacher.home');
        } catch (\Exception $ex) {
            DB::rollBack();
            return 0;
        }
    }
}
