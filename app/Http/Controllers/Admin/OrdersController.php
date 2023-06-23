<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\VoucherCode;
use App\Models\ProductCategory;
use App\Models\OrderProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class OrdersController extends Controller
{
    public function order_image(Request $request)
    {
        foreach ($request->images as $key => $image) {
            $image = explode(";", $image)[1];
            $image = explode(",", $image)[1];
            $image = str_replace(" ", "+", $image);
            $image = base64_decode($image);
            $path = '/uploads/pdf_orders/' . $request->code . '.png';
            file_put_contents('public' . $path, $image);
        }
        return asset($path);
    }
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
        if(false){
            $order = Order::findOrFail($id);
            $setting = GeneralSetting::first();
            $products = OrderProduct::where('order_id', $id)
                ->with('product')
                ->groupBy('product_id', 'attributes', 'price')
                ->selectRaw('sum(total_cost) as total_cost, sum(quantity) as quantity, product_id, attributes, price')
                ->get();

            // $pdf = Pdf::loadView('admin.cashierModes.partials.pdf', compact('order', 'products'));
            // $path ='/uploads/pdf_orders/'.$order->code . '.pdf';
            // $pdf->save(public_path() . $path);

            $cashier = json_decode($setting->cashier_printer);
            $kitchen = json_decode($setting->kitchen_printer);

            $cashier_printer = $cashier->printer ?? '';
            $kitchen_printer = $kitchen->printer ?? '';

            $cashier_print_times = $cashier->print_times ?? 1;
            $kitchen_print_times = $kitchen->print_times ?? 1;

            if ($order->order_from == 'teacher') {
                if(!$order->viewed){
                    $order->viewed = 1;
                    $order->save();
                }
            }
            return view('admin.cashierModes.partials.print', compact('order', 'products' ,'cashier_printer','kitchen_printer','cashier_print_times','kitchen_print_times'));

        }elseif(false){
            $url = 'F:\Work\Projects\Ebtikar web v.1\index.html'; // Replace with the URL of the HTML page you want to print

            $html = file_get_contents($url); // Get the HTML contents of the page

            $connector = new WindowsPrintConnector("Microsoft Print to PDF"); // Replace "Printer Name" with the name of your printer

            $printer = new Printer($connector);
            $printer->text($html); // Print the HTML contents of the page

            $printer->cut();
            $printer->close();
        }elseif(false){
            $generalsetting = GeneralSetting::first();
            $order = Order::findOrFail($id);
            $date = explode(' ',$order->created_at,2);
            $code = explode('-',$order->code);

            $cashier = json_decode($generalsetting->cashier_printer);
            $kitchen = json_decode($generalsetting->kitchen_printer);
            $cashier_printer = $cashier->printer ?? '';
            $kitchen_printer = $kitchen->printer ?? '';
            $cashier_print_times = $cashier->print_times ?? 1;
            $kitchen_print_times = $kitchen->print_times ?? 1;

            $products = OrderProduct::where('order_id', $id)
                                    ->with('product')
                                    ->groupBy('product_id', 'attributes', 'price')
                                    ->selectRaw('sum(total_cost) as total_cost, sum(quantity) as quantity, product_id, attributes, price')
                                    ->get();

            // Set params
            $store_name = $generalsetting->website_title;
            $order_code = $code[1]  ?? $order->code;
            $currency = 'LE ';
            $cashier = $order->created_by->name ?? 'admin';
            $image_path = $generalsetting->logo ? url($generalsetting->logo->getUrl()) : '';

            // Set items

            foreach($products as $order_product){
                $items[] = [
                    'name' => $order_product->product->name ?? '',
                    'qty' => $order_product->quantity,
                    'price' => $order_product->price,
                ];
            }

            // Init printer
            $printer = new ReceiptPrinter;
            $printer->init(
                'windows',
                $cashier
            );
            // Set store info
            $printer->setStore('',$store_name,'','','','');

            // Set currency
            $printer->setCurrency($currency);

            // Add items
            foreach ($items as $item) {
                $printer->addItem(
                    $item['name'],
                    $item['qty'],
                    $item['price']
                );
            }

            // Calculate total
            $printer->calculateSubTotal();
            $printer->calculateGrandTotal();

            // Set orderCode
            $printer->setOrderCode($order_code);

            // Set cashier
            $printer->setCashier($cashier);

            // Set date
            $printer->setDate($date[0] . $date[1]);

            // Set logo
            // $printer->setLogo($image_path);

            // Print receipt
            $printer->printReceipt();

            if ($order->order_from == 'teacher') {
                if(!$order->viewed){
                    $order->viewed = 1;
                    $order->save();
                }
            }

            return 'success';

        }else{

            $order = Order::findOrFail($id);
            $setting = GeneralSetting::first();
            $products = OrderProduct::where('order_id', $id)
                ->with('product')
                ->groupBy('product_id', 'attributes', 'price')
                ->selectRaw('sum(total_cost) as total_cost, sum(quantity) as quantity, product_id, attributes, price')
                ->get();

            $cashier = json_decode($setting->cashier_printer);
            $kitchen = json_decode($setting->kitchen_printer);

            $cashier_printer = $cashier->printer ?? '';
            $kitchen_printer = $kitchen->printer ?? '';

            $cashier_print_times = $cashier->print_times ?? 1;
            $kitchen_print_times = $kitchen->print_times ?? 1;

            if ($order->order_from == 'teacher') {
                if(!$order->viewed){
                    $order->viewed = 1;
                    $order->save();
                }
            }
            return view('admin.cashierModes.partials.print', compact('order', 'products' ,'cashier_printer','kitchen_printer','cashier_print_times','kitchen_print_times'));

        }
    }

    public function print00($id){

        /* Most printers are open on port 9100, so you just need to know the IP
        * address of your receipt printer, and then fsockopen() it on that port.
        */
        try {
            $connector = new NetworkPrintConnector("192.168.100.11", 9100);

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer -> text("Hello World! this network print\n");
            $printer -> cut();

            /* Close printer */
            $printer -> close();
        } catch (\Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
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
