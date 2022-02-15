<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\VoucherCode;
use App\Models\ProductCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Session;
use Alert;
use Carbon\Carbon;

class OrdersController extends Controller
{

    public function print($id){
        $order = Order::findOrFail($id);

        $order->load('products.product'); 
        
        return view('admin.cashierModes.partials.print',compact('order'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with(['voucher_code', 'products', 'created_by'])->select(sprintf('%s.*', (new Order())->table));
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
                return $row->id ? $row->id : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('total_cost', function ($row) {
                return $row->total_cost ? $row->total_cost : '';
            }); 

            $table->rawColumns(['actions', 'placeholder']);

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

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->all());
        $order->products()->sync($request->input('products', []));

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');  

        $order->load('voucher_code', 'products.product', 'created_by');

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
        
        return view('admin.cashierModes.edit', compact('order', 'categories', 'vouchercodes'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());
        $order->products()->sync($request->input('products', []));

        return redirect()->route('admin.orders.index');
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

        $isAdmin = auth()->user()->roles->contains(1);

        if (!$isAdmin) {
            $created_at = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $order->created_at)->format('Y-m-d H:i:s');
            if(Carbon::parse($created_at)->addMinutes(15)->isPast()){ 
                Alert::warning('لم يتم تنفيذ الأمر', 'تعدي الوقت المسموح به للمسح برجاء التواصل مع الأدمن لتنفيذ الأمر '); 
                return 1;
            }
        }

        $order->delete(); 

        Alert::success('تم بنجاح', 'تم  حذف الطلب بنجاح '); 
        return 1;
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        Order::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
