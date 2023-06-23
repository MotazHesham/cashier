<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\VoucherCode;
use App\Models\AttributeProduct;
use App\Models\GeneralSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class CashierModeController extends Controller
{
    public function qr_scanner(Request $request)
    {
        $type = $request->type;
        return view('admin.cashierModes.qr_code_scanner', compact('type'));
    }

    public function qr_output(Request $request)
    {
        $user = User::find($request->code);
        $balance = $user->current_balance();
        $isStoreForm = $request->type == 'store' ? 1 : 0;
        $photo = $user->photo ? $user->photo->getUrl('preview') : "";
        if ($user) {
            if ($balance) {
                if ($balance >= $request->total) {
                    $output = '<div class="card" style="height:auto;margin:15px 0px">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="' . $photo .'" class="rounded" width="155" >
                                        </div>
                                        <div class="col-md-8">
                                            <div class="text-center">
                                                <h3> ' . $user->name . ' </h3>
                                                <div class="c-callout c-callout-info b-t-1 b-r-1 b-b-1">
                                                    <small class="text-muted">Wallet Balance</small><br>
                                                    <strong class="h4">EGP ' . $balance . ' </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-success">Ready To Use Qr Code</div>
                            <button class="btn btn-primary" onclick="submit_pay_form(' . $isStoreForm . ')"
                                style="border-radius:10px;background: #69becf;border-color: #69becf; padding: 22px; font-size: 34px;">
                                دفع
                            </button>
                            ';
                    return [
                        'status' => true,
                        'message' =>  $output,
                        'user_id' => $request->code
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => "<div class='alert alert-danger'>Balance Not Enough</div>"
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => "<div class='alert alert-danger'>No Balance Available</div>"
                ];
            }
        } else {
            return [
                'status' => false,
                'message' => "<div class='alert alert-danger'>Not Found The Qr Code Owner</div>"
            ];
        }
    }

    public function index()
    {
        abort_if(Gate::denies('cashier_mode_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $now_date = date('Y-m-d', strtotime('now'));

        $categories = ProductCategory::with('products.attributeProduct')->get();
        $vouchercodes = VoucherCode::where('start_date', '<=', $now_date)->where('end_date', '>=', $now_date)->get();
        Session::put('counter', 0);

        return view('admin.cashierModes.add', compact('categories', 'vouchercodes'));
    }

    public function edit(Request $request)
    {
        $order = Order::where('code', $request->code)->first();

        if (!$order) {
            Alert::warning('Order Not Found');
            return redirect()->route('admin.cashier-modes.index');
        }

        $order->load('products.product');
        $now_date = date('Y-m-d', strtotime('now'));

        $categories = ProductCategory::with('products.attributeProduct')->get();
        $vouchercodes = VoucherCode::where('start_date', '<=', $now_date)->where('end_date', '>=', $now_date)->get();

        Session::put('counter', 0);


        $isAdmin = auth()->user()->roles->contains(1);

        if (!$isAdmin) {
            $created_at = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $order->created_at)->format('Y-m-d H:i:s');
            if (Carbon::parse($created_at)->addMinutes(10)->isPast()) {
                Alert::warning('لم يتم تنفيذ الأمر', 'تعدي الوقت المسموح به للتعديل برجاء التواصل مع الأدمن لتنفيذ الأمر ');
                return redirect()->route('admin.cashier-modes.index');
            }
        }

        return view('admin.cashierModes.edit', compact('order', 'categories', 'vouchercodes'));
    }
    public function add_product(Request $request)
    {
        Session::put('counter', Session::get('counter') + 1);
        $product = Product::findOrFail($request->product_id);

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
        return view('admin.cashierModes.partials.add_product', compact('product', 'attributes', 'quantity', 'product_cost_with_extra'));
    }
    public function store(Request $request)
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
                return [
                    'status' => false,
                    'message' => 'من فضلك اختر منتج أولا',
                ];
            }
            $order = Order::create([
                'code' => $code,
                'entry_date' => date('Y-m-d', strtotime('now')),
                'paid_up' => $request->paid_up,
                'total_cost' => 0,
                'voucher_code_id' => $request->voucher_code_id,
                'payment_type' => $request->payment_type,
                'order_from' => 'cashier',
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

            if ($request->payment_type == 'qr_code') {
                $user = User::find($request->qr_user_id);
                $balance = $user->current_balance();
                if ($balance < $order->total_cost) {
                    DB::rollBack();
                    return [
                        'status' => false,
                        'message' => 'Balance Not Enough',
                    ];
                }
                User::find($request->qr_user_id)->withdraw($order->total_cost, ['info' => $user->current_balance(), 'order' => $order->code, 'meta' => 'عملية سحب لشراء طلب']);
                $user->current_balance();

                $order->user_id = $request->qr_user_id;
            }

            $order->save();

            $order->load('products.product');

            $setting = GeneralSetting::first();
    
            $cashier = json_decode($setting->cashier_printer);
            $kitchen = json_decode($setting->kitchen_printer);
    
            $cashier_printer = $cashier->printer ?? '';
            $kitchen_printer = $kitchen->printer ?? '';
            $cashier_printer_copies = $cashier->print_times ?? 1;
            $kitchen_printer_copies = $kitchen->print_times ?? 1;
    
            $link = route('admin.orders.print', $order->id);   
            DB::commit();
            return [
                'status' => true,
                'cashier_printer' => $cashier_printer,
                'kitchen_printer' => $kitchen_printer,
                'link' => $link,
                '$cashier_printer_copies' => $cashier_printer_copies,
                '$kitchen_printer_copies' => $kitchen_printer_copies,
            ];
        } catch (\Exception $ex) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'someting went wrong',
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = Order::findOrFail($request->order_id);
            $old_total_cost = $order->total_cost; // for orders have payment_type => qr_code

            if ($request->payment_type == 'qr_code') {
                $user = User::find($request->qr_user_id);
                $remain = $order->total_cost - $old_total_cost;
                if ($user->current_balance() < $remain) {
                    return [
                        'status' => false,
                        'message' => 'Balance Not Enough',
                    ];
                }
            }
            // check ability to edit
            $isAdmin = auth()->user()->roles->contains(1);

            if (!$isAdmin) {
                $created_at = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $order->created_at)->format('Y-m-d H:i:s');
                if (Carbon::parse($created_at)->addMinutes(10)->isPast()) {
                    return [
                        'status' => false,
                        'message' => 'تعدي الوقت المسموح به للتعديل برجاء التواصل مع الأدمن لتنفيذ الأمر ',
                    ];
                }
            }
            //----------------------

            $order->load('products.product');

            foreach ($order->products as $order_product) {
                $order_product->delete();
            }

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

            if ($request->payment_type == 'qr_code') {
                if ($remain > 0) {
                    $user->withdraw($remain, ['info' => $user->current_balance(), 'order' => $order->code, 'meta' => 'عملية سحب بعد تعديل الطلب']);
                } elseif ($remain < 0) {
                    $user->deposit(($old_total_cost - $order->total_cost), ['info' => $user->current_balance(), 'order' => $order->code, 'meta' => 'عملية أضافة بعد تعديل الطلب']);
                }

                $order->user_id = $request->qr_user_id;
            }

            $order->save();

            $order->update([
                'paid_up' => $request->paid_up,
                'discount' => $discount,
                'voucher_code_id' => $request->voucher_code_id,
            ]);

            $order->load('products.product');

            DB::commit();
            return [
                'status' => true,
                'message' => 'success',
                'link' => route('admin.orders.print', $order->id),
            ];
        } catch (\Exception $ex) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'someting wrong'
            ];
        }
    }
}
