<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\VoucherCode;
use App\Models\ProductCategory;
use App\Models\OrderProduct;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Session;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;


class OrdersController extends Controller
{
    public function details(Request $request)
    {
        $order = Order::where('code', $request->code)->first();
        $order->load('products');
        return view('admin.orders.details', compact('order'));
    }
    public function pay_user(Request $request)
    {
        if ($request->payment_type != 'cash') {
            Alert::warring('cant pay');
            return redirect()->route('admin.orders.show', $request->order_id);
        }
        $products = OrderProduct::where('user_id', $request->user_id)->where('order_id', $request->order_id)->get();

        foreach ($products as $raw) {
            $raw->payment_type = 'cash';
            $raw->save();
        }
        Alert::success('تم الدفع كاش');
        return redirect()->route('admin.orders.show', $request->order_id);
    }
    public function qr_scanner(Request $request)
    {
        $user_id = $request->user_id;
        $order_id = $request->order_id;
        return view('admin.orders.qr_code_scanner', compact('user_id', 'order_id'));
    }


    public function qr_output(Request $request)
    {
        if ($request->code != $request->user_id) {
            return [
                'status' => false,
                'message' => "<div class='alert alert-danger'>Not The Same User with Qr Code</div>"
            ];
        }
        $order = Order::findOrFail($request->order_id);
        $user = User::find($request->code);
        $total = OrderProduct::where('order_id', $request->order_id)->where('user_id', $request->user_id)->sum('total_cost') ?? 0;
        $balance = $user->current_balance();
        if ($user) {
            if ($balance) {
                if ($balance >= $total) {
                    $user->withdraw($total, ['info' => $balance, 'order' => $order->code, 'meta' => 'عملية سحب بعد عملية شراء في طلب مجمع']);
                    foreach (OrderProduct::where('order_id', $request->order_id)->where('user_id', $request->user_id)->get() as $raw) {
                        $raw->payment_type = 'qr_code';
                        $raw->save();
                    }
                    $output = '<div class="card" style="height:auto;margin:15px 0px">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80" class="rounded" width="155" >
                                        </div>
                                        <div class="col-md-8">
                                            <div class="text-center">
                                                <h3> ' . $user->name . ' </h3>
                                                <h5> ' . $user->phone . ' </h5>
                                                <div class="c-callout c-callout-info b-t-1 b-r-1 b-b-1">
                                                    <small class="text-muted">Wallet Balance After Paid</small><br>
                                                    <strong class="h4">EGP ' . $user->current_balance() . ' </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-success">تم الدفع بواسطة ال QR Code</div>
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


    public function print2($id)
    {
        $order = Order::findOrFail($id);
        $user_products = OrderProduct::where('order_id', $id)
            ->with(['product', 'user'])
            ->get()
            ->groupBy('user_id');
        //return $products;
        return view('admin.cashierModes.partials.print_students', compact('order', 'user_products'));
    }

    public function print($id)
    {
        $order = Order::findOrFail($id);

        if ($order->order_from == 'teacher') {
            $order->viewed = 1;
            $order->save();
        }
        $products = OrderProduct::where('order_id', $id)
            ->with('product')
            ->groupBy('product_id', 'attributes', 'price')
            ->selectRaw('sum(total_cost) as total_cost, sum(quantity) as quantity, product_id, attributes, price')
            ->get();
        //  return $products;
        // $pdf = Pdf::loadView('admin.cashierModes.partials.pdf', compact('order', 'products'));
        // $path ='/uploads/pdf_orders/'.$order->code . '.pdf';
        // $pdf->save(public_path() . $path);
        return view('admin.cashierModes.partials.print', compact('order', 'products'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with(['voucher_code', 'products', 'created_by'])->orderBy('viewed')->select(sprintf('%s.*', (new Order())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = 'order_edit';
                $deleteGate = 'order_delete';
                $crudRoutePart = 'orders';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                $new = !$row->viewed ? '<br><span class="badge badge-danger">طلب جديد</span>' : '';
                return $row->id ? $row->id . $new : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('payment_type', function ($row) {
                return $row->payment_type ? Order::PAYMENT_TYPE_SELECT[$row->payment_type] : '';
            });
            $table->editColumn('total_cost', function ($row) {
                return $row->total_cost ? $row->total_cost : '';
            });
            $table->editColumn('order_from', function ($row) {
                $order_from = $row->order_from == 'teacher' ? '<br><span class="badge badge-dark">طلب مجمع</span>' : 'كاشير';
                return $row->order_from ? $order_from : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'order_from', 'id']);

            return $table->make(true);
        }

        return view('admin.orders.index');
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucher_codes = VoucherCode::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $products = Product::pluck('name', 'id');

        return view('admin.orders.create', compact('products', 'voucher_codes'));
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($order->order_from != 'cashier') {
            Alert::error('Cant Edit This Order');
            return redirect()->back();
        }

        $order->load('voucher_code', 'products.product', 'created_by');

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

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('voucher_code', 'products', 'created_by');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($order->order_from != 'cashier') {
            Alert::error('لا يمكن الحذف حاليا');
            return 1;
        }

        $isAdmin = auth()->user()->roles->contains(1);

        if (!$isAdmin) {
            $created_at = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $order->created_at)->format('Y-m-d H:i:s');
            if (Carbon::parse($created_at)->addMinutes(15)->isPast()) {
                Alert::warning('لم يتم تنفيذ الأمر', 'تعدي الوقت المسموح به للمسح برجاء التواصل مع الأدمن لتنفيذ الأمر ');
                return 1;
            }
        }

        if ($order->payment_type == 'qr_code') {
            $user = User::find($order->user_id);
            $user->deposit($order->total_cost, ['info' => $user->current_balance(), 'order' => $order->code, 'meta' => 'عملية أضافة بعد حذف أوردر']);
            $user->current_balance();
        }
        $order->delete();

        Alert::success('تم بنجاح', 'تم  حذف الطلب بنجاح ');
        return 1;
    }
}
