<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCashierModeRequest;
use App\Http\Requests\StoreCashierModeRequest;
use App\Http\Requests\UpdateCashierModeRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CashierModeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cashier_mode_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.cashierModes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('cashier_mode_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.cashierModes.create');
    }

    public function store(StoreCashierModeRequest $request)
    {
        $cashierMode = CashierMode::create($request->all());

        return redirect()->route('admin.cashier-modes.index');
    }

    public function edit(CashierMode $cashierMode)
    {
        abort_if(Gate::denies('cashier_mode_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.cashierModes.edit', compact('cashierMode'));
    }

    public function update(UpdateCashierModeRequest $request, CashierMode $cashierMode)
    {
        $cashierMode->update($request->all());

        return redirect()->route('admin.cashier-modes.index');
    }

    public function show(CashierMode $cashierMode)
    {
        abort_if(Gate::denies('cashier_mode_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.cashierModes.show', compact('cashierMode'));
    }

    public function destroy(CashierMode $cashierMode)
    {
        abort_if(Gate::denies('cashier_mode_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cashierMode->delete();

        return back();
    }

    public function massDestroy(MassDestroyCashierModeRequest $request)
    {
        CashierMode::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
