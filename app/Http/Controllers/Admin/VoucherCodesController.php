<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVoucherCodeRequest;
use App\Http\Requests\StoreVoucherCodeRequest;
use App\Http\Requests\UpdateVoucherCodeRequest;
use App\Models\VoucherCode;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoucherCodesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('voucher_code_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucherCodes = VoucherCode::all();

        return view('admin.voucherCodes.index', compact('voucherCodes'));
    }

    public function create()
    {
        abort_if(Gate::denies('voucher_code_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.voucherCodes.create');
    }

    public function store(StoreVoucherCodeRequest $request)
    {
        $voucherCode = VoucherCode::create($request->all());

        return redirect()->route('admin.voucher-codes.index');
    }

    public function edit(VoucherCode $voucherCode)
    {
        abort_if(Gate::denies('voucher_code_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.voucherCodes.edit', compact('voucherCode'));
    }

    public function update(UpdateVoucherCodeRequest $request, VoucherCode $voucherCode)
    {
        $voucherCode->update($request->all());

        return redirect()->route('admin.voucher-codes.index');
    }

    public function show(VoucherCode $voucherCode)
    {
        abort_if(Gate::denies('voucher_code_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucherCode->load('voucherCodeOrders');

        return view('admin.voucherCodes.show', compact('voucherCode'));
    }

    public function destroy(VoucherCode $voucherCode)
    {
        abort_if(Gate::denies('voucher_code_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucherCode->delete();

        return back();
    }

    public function massDestroy(MassDestroyVoucherCodeRequest $request)
    {
        VoucherCode::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
