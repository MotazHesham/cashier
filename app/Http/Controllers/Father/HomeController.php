<?php

namespace App\Http\Controllers\Father;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Father;
use App\Models\User;
use App\Models\Student;
use App\Models\Order;
use Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function details(Request $request){
       $order = Order::where('code',$request->code)->first();
       $order->load('products');
       return view('admin.orders.details',compact('order'));
     }

    public function index()
    {
        $user = Auth::user();
        $user->load('transactions');

        $father = Father::where('user_id',$user->id)->get()->first();

        $sons = User::whereIn('id',Student::where('father_id',$father->id)->get()->pluck('user_id'))->get();

        $transactions = $user->transactions()->orderBy('created_at','desc')->paginate(5);
        return view('father.home',compact('user','transactions','sons'));
    }
}
