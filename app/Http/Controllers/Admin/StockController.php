<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Requests\StoreStockOperationRequest;
use App\Models\Stock;
use App\Models\StockOperation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Item;
use Alert;

class StockController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $raws = Stock::with('stockOperations')->get();

        return view('admin.stock.index', compact('raws'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $items = Item::pluck('item', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.stock.create',compact('items'));
    }

    public function store(StoreStockRequest $request){

        $validated_request = $request->all();
        $validated_request['total_cost'] = $request->price * $request->quantity;
        $stock = Stock::create($validated_request);
        $item = $stock->item;
        $item->current_stock += $request->quantity;
        $item->save();
        return redirect()->route('admin.stock.index');
    }

    public function create_operation(StoreStockOperationRequest $request){
      $stock = Stock::findOrFail($request->stock_id);
      if($stock->quantity < $request->quantity){
        Alert::error('الكمية اكبر من المتاحة');
        return redirect()->back();
      }

      $stock_operation = StockOperation::create($request->all());

      $item = $stock->item;
      $item->current_stock -= $request->quantity;
      $item->save();
      Alert::success('تم السحب بنجاح');
      return redirect()->route('admin.stock.index');
    }

    public function operation_history(Request $request){
        $stock = Stock::findOrFail($request->id);
        return view('admin.stock.operations',compact('stock'));
    }

    public function show(Stock $stock)
    {
        abort_if(Gate::denies('stock_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.stock.show', compact('stock'));
    }

    public function destroy(Stock $stock)
    {
        abort_if(Gate::denies('stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $item = $stock->item;
        if($stock->delete()){
          $item->current_stock -= $stock->quantity;
          $item->save();
        }

        return back();
    }


}
