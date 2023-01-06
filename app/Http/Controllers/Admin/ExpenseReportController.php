<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Item;
use App\Models\Payment;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {

        global $start_date,$end_date,$m,$y;

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $y = $request->has('y') ? $request->y : request()->query('y', Carbon::now()->year);
        $m = $request->has('m') ? $request->m : request()->query('m', Carbon::now()->month);

        if($request->has('start_date')){
            $expenses = Expense::with('expense_category')
                ->whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $start_date)->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $end_date)->format('Y-m-d')
                ]);

            $incomes = Income::with('income_category')
                ->whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $start_date)->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $end_date)->format('Y-m-d')
                ]);

            $orders = Order::whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $start_date)->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $end_date)->format('Y-m-d')
                ]);

            $orderProductsCash = OrderProduct::where('payment_type','cash')->whereHas('order',function($q){
                $q->whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $GLOBALS['start_date'])->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $GLOBALS['end_date'])->format('Y-m-d')
                ]);
            });

            $orderProducts = OrderProduct::whereHas('order',function($q){
                $q->whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $GLOBALS['start_date'])->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $GLOBALS['end_date'])->format('Y-m-d')
                ]);
            });

            $trashed_orders = Order::onlyTrashed()->whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $start_date)->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $end_date)->format('Y-m-d')
                ]);


            $stock = Stock::whereBetween('entry_date',
                [   Carbon::createFromFormat(config('panel.date_format'), $start_date)->format('Y-m-d')
                    , Carbon::createFromFormat(config('panel.date_format'), $end_date)->format('Y-m-d')
                ]);


            $payments = Payment::whereBetween('created_at',
                [   Carbon::createFromFormat(config('panel.date_format') . ' H:i:s', $start_date . ' 00:00:00')->format('Y-m-d H:i:s')
                    , Carbon::createFromFormat(config('panel.date_format') . ' H:i:s', $end_date . ' 23:59:59')->format('Y-m-d H:i:s')
                ]);
        }else{

            $expenses = Expense::with('expense_category')
                ->whereMonth('entry_date',$m)->whereYear('entry_date',$y);

            $incomes = Income::with('income_category')
                ->whereMonth('entry_date',$m)->whereYear('entry_date',$y);

            $orders = Order::whereMonth('entry_date',$m)->whereYear('entry_date',$y);

            $orderProducts = OrderProduct::whereHas('order',function($q){
                $q->whereMonth('entry_date',$GLOBALS['m'])->whereYear('entry_date',$GLOBALS['y']);
            });

            $orderProductsCash = OrderProduct::where('payment_type','cash')->whereHas('order',function($q){
                $q->whereMonth('entry_date',$GLOBALS['m'])->whereYear('entry_date',$GLOBALS['y']);
            });

            $trashed_orders = Order::onlyTrashed()->whereMonth('entry_date',$m)->whereYear('entry_date',$y);


            $stock = Stock::whereMonth('entry_date',$m)->whereYear('entry_date',$y);

            $payments = Payment::whereMonth('created_at',$m)->whereYear('created_at',$y);
        }

        $groupedExpenses = $expenses->whereNotNull('expense_category_id')
                                    ->orderBy('amount', 'desc')
                                    ->get()
                                    ->groupBy('expense_category_id');
        $groupedIncomes  = $incomes->whereNotNull('income_category_id')
                                    ->orderBy('amount', 'desc')
                                    ->get()
                                    ->groupBy('income_category_id');
        $groupedOrderProducts  = $orderProducts->groupBy('product_name','attributes')
                                                ->select('attributes')
                                  ->selectRaw('sum(total_cost) as total_cost,sum(quantity) as quantity, product_name')
                                  ->get();
        $stockByItems = $stock->orderBy('entry_date','asc')
                              ->get()
                              ->groupBy('item_id');
        $groupedStock = $stock->groupBy('item_id')
                                ->select('item_id')
                                ->selectRaw('sum(total_cost) as total_cost,sum(quantity) as quantity, item_id')
                                ->get();
        $groupedOrders = $orders->get()
                                ->groupBy('payment_type');
        $groupedPayments = $payments->get()
                                ->groupBy('type');

        $expensesTotal   = $expenses->sum('amount');
        $incomesTotal    = $incomes->sum('amount');
        $ordersTotal    = $groupedOrders['cash'] ?? '' ? $groupedOrders['cash']->sum('total_cost') : 0;
        $ordersTotal += $orderProductsCash->sum('total_cost'); // this products in orders from teacher for students pay their product in cash
        $trashedOrdersTotal    = $trashed_orders->sum('total_cost') ?? 0;
        $stockTotal = $groupedStock->sum('total_cost');
        $payments_charge = $groupedPayments['charge'] ?? '' ? $groupedPayments['charge']->sum('amount') : 0;
        $payments_withdraw = $groupedPayments['withdraw'] ?? '' ? $groupedPayments['withdraw']->sum('amount') : 0;

        $trashedOrdersIncomes  = $trashed_orders->get();
        $profit          = ($incomesTotal + $ordersTotal + $payments_charge) - ($expensesTotal + $stockTotal + $payments_withdraw);

        $expensesSummary = [];
        foreach ($groupedExpenses as $exp) {
            foreach ($exp as $line) {
                if (!isset($expensesSummary[$line->expense_category->name])) {
                    $expensesSummary[$line->expense_category->name] = [
                        'name'   => $line->expense_category->name,
                        'amount' => 0,
                    ];
                }
                $expensesSummary[$line->expense_category->name]['amount'] += $line->amount;
            }
        }


          $expensesSummary['أضافة أصناف للمخزن'] = [
              'name' => 'أضافة أصناف للمخزن',
              'amount' => $stockTotal,
              'details' => true,
          ];


          $expensesSummary['أسترجاع مبالغ'] = [
              'name' => 'أسترجاع مبالغ',
              'amount' => $payments_withdraw,
              'details' => false,
          ];

        $incomesSummary = [];
        foreach ($groupedIncomes as $inc) {
            foreach ($inc as $line) {
                if (!isset($incomesSummary[$line->income_category->name])) {
                    $incomesSummary[$line->income_category->name] = [
                        'name'   => $line->income_category->name,
                        'amount' => 0,
                    ];
                }
                $incomesSummary[$line->income_category->name]['amount'] += $line->amount;
            }
        }

        $incomesSummary['الطلبات'] = [
            'name' => 'الطلبات',
            'amount' => $ordersTotal,
            'expanded' => true,
            'items' => [
            ],
        ];

        foreach($groupedOrders as $key => $ord){
          if($key == 'cash'){
            $title = 'حساب كاش';
          }elseif($key == 'qr_code'){
            $title = 'حساب عن طريق qr';
          }else{
            break;
          }
          $incomesSummary['الطلبات']['items'][$title] = [
              'name'   => $title,
              'amount' => $key == 'cash' ? $ordersTotal : $ord->sum('total_cost'),
          ];
        }

        $incomesSummary['التحويلات'] = [
            'name' => 'التحويلات',
            'amount' => $payments_charge,
        ];
        //return $incomesSummary;
        if($request->has('print')){
            return view('admin.expenseReports.report_print')->with([
                                                                        'products_report' => $groupedOrderProducts,
                                                                        'year' => $request->y,
                                                                        'month' => $request->m,
                                                                        'start_date' => $request->start_date,
                                                                        'end_date' => $request->end_date,
                                                                        'ordersTotal' => $ordersTotal,
                                                                    ]);
        }else{
            return view('admin.expenseReports.index', compact(
                'expensesSummary',
                'incomesSummary',
                'expensesTotal',
                'incomesTotal',
                'ordersTotal',
                'payments_withdraw',
                'payments_charge',
                'stockTotal',
                'start_date',
                'end_date',
                'm',
                'y',
                'trashedOrdersTotal',
                'trashedOrdersIncomes',
                'profit',
                'stockByItems'
            ));
        }
    }
}
