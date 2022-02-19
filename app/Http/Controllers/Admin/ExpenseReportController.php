<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        global $from,$to,$start_date,$end_date;

        $from = Carbon::parse(sprintf(
            '%s-%s-01',
            request()->query('y', Carbon::now()->year),
            request()->query('m', Carbon::now()->month)
        ));
        $to      = clone $from;
        $to->day = $to->daysInMonth;

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
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
        }else{
            $expenses = Expense::with('expense_category')
                ->whereBetween('entry_date', [$from, $to]);

            $incomes = Income::with('income_category')
                ->whereBetween('entry_date', [$from, $to]);

            $orders = Order::whereBetween('entry_date', [$from, $to]);

            $orderProducts = OrderProduct::whereHas('order',function($q){
                $q->whereBetween('entry_date', [$GLOBALS['from'], $GLOBALS['to']]);
            });

            $trashed_orders = Order::onlyTrashed()->whereBetween('entry_date', [$from, $to]);
        }
        $expensesTotal   = $expenses->sum('amount');
        $incomesTotal    = $incomes->sum('amount');
        $ordersTotal    = $orders->sum('total_cost') ?? 0;
        $trashedOrdersTotal    = $trashed_orders->sum('total_cost') ?? 0;
        $groupedExpenses = $expenses->whereNotNull('expense_category_id')->orderBy('amount', 'desc')->get()->groupBy('expense_category_id');
        $groupedIncomes  = $incomes->whereNotNull('income_category_id')->orderBy('amount', 'desc')->get()->groupBy('income_category_id'); 
        $groupedOrderProducts  = $orderProducts->groupBy('product_name','attributes')->select('attributes')->selectRaw('sum(total_cost) as total_cost,sum(quantity) as quantity, product_name')->get(); 
        //return $groupedOrderProducts;
        $trashedOrdersIncomes  = $trashed_orders->get();
        $profit          = ($incomesTotal + $ordersTotal) - $expensesTotal;

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
        ]; 

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
                'start_date',
                'end_date',
                'trashedOrdersTotal',
                'trashedOrdersIncomes', 
                'profit'
            ));
        }
    }
}
