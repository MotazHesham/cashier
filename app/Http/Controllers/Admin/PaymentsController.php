<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use DB;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $transactions = DB::table('transactions')->get();
        $users_balance = $transactions->sum('amount');
        abort_if(Gate::denies('payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Payment::with(['user'])->select(sprintf('%s.*', (new Payment())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            // $table->editColumn('actions', function ($row) {
            //     $viewGate = 'payment_show';
            //     $editGate = 'payment_edit';
            //     $deleteGate = 'payment_delete';
            //     $crudRoutePart = 'payments';
            //
            //     return view('partials.datatablesActions', compact(
            //       'viewGate',
            //       'editGate',
            //       'deleteGate',
            //       'crudRoutePart',
            //       'row'
            //   ));
            // });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('payment_type', function ($row) {
                return $row->payment_type ? Payment::PAYMENT_TYPE_SELECT[$row->payment_type] : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? Payment::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('payment_status', function ($row) {
                return $row->payment_status ? Payment::PAYMENT_STATUS_SELECT[$row->payment_status] : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.payments.index',compact('users_balance','transactions'));
    }

    public function create()
    {
        abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.payments.create', compact('users'));
    }

    public function store(StorePaymentRequest $request)
    {

        $user = User::findOrFail($request->user_id);
        if($request->type == 'withdraw'){
          if($user->current_balance() < $request->amount){
            $error_message = 'الرصيد ' . $user->current_balance() . ' لا يكفي لأتمام عملية السحب';
            Alert::error($error_message);
            return redirect()->back();
          }
        }

        $payment = Payment::create($request->all());
        if($payment){
            if($request->type == 'withdraw'){
              $user->withdraw($request->amount,['info' => $user->current_balance(),'meta' => 'عملية سحب من الحساب']);
            }elseif($request->type == 'charge'){
              $user->deposit($request->amount,['info' => $user->current_balance(),'meta' => 'عملية أضافة للحساب']);
            }
        }
        return redirect()->route('admin.payments.index');
    }

    public function edit(Payment $payment)
    {
        abort_if(Gate::denies('payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $payment->load('user');

        return view('admin.payments.edit', compact('payment', 'users'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->all());

        return redirect()->route('admin.payments.index');
    }

    public function show(Payment $payment)
    {
        abort_if(Gate::denies('payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->load('user');

        return view('admin.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        abort_if(Gate::denies('payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->delete();

        return back();
    }

    public function massDestroy(MassDestroyPaymentRequest $request)
    {
        Payment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
