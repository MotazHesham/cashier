<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\User;
use App\Models\Father;
use App\Models\Student;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use Auth;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = Payment::with(['user'])->where('user_id',Auth::id())->select(sprintf('%s.*', (new Payment())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

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
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('father.payments.index');
    }

    public function transfer(Request $request){
      $user = Auth::user();
      $ids[] = $user->id;
      $father = Father::where('user_id',$user->id)->get()->first();
      $sons = User::whereIn('id',Student::where('father_id',$father->id)->get()->pluck('user_id'))->get();

      foreach($sons as $son){
        $ids[] = $son->id;
      }

      // security wise => ensure that users is the father and sons
      if(!in_array($request->from,$ids) || !in_array($request->to,$ids)){
        Alert::error('SomeThing Went Worng');
        return redirect()->back();
      }

      // cant transfer to same account
      if($request->from == $request->to){
        Alert::error('لا يمكن التحويل لنفس الحساب');
        return redirect()->back();
      }

      $from_user = User::findOrFail($request->from);
      $to_user = User::findOrFail($request->to);

      // ensure the balance is enough
      if($from_user->current_balance() < $request->amount){
          $error_message = 'الرصيد ' . $from_user->current_balance() . ' لا يكفي لأتمام عملية السحب';
          Alert::error($error_message);
          return redirect()->back();
      }

      //begin tranfser
      $meta = [
        'from' => $from_user->name,
        'to' => $to_user->name
      ];
      $from_user->transfer(
                    $to_user,
                    $request->amount,
                    [
                      'info' => 'from ->' . $from_user->current_balance() . '&to ->' . $to_user->current_balance(),
                      'meta' => 'عملية تحويل نقود من حساب ' . $meta['from'] . ' إلي حساب ' . $meta['to']
                    ]);

      Alert::success(' تم تحويل' . $request->amount . ' بنجاح');
      return redirect()->back();
    }

}
