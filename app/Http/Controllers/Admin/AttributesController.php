<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAttributeRequest;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Models\Attribute;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AttributesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('attribute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Attribute::query()->select(sprintf('%s.*', (new Attribute())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'attribute_show';
                $editGate = 'attribute_edit';
                $deleteGate = 'attribute_delete';
                $crudRoutePart = 'attributes';

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
            $table->editColumn('attribute', function ($row) {
                return $row->attribute ? $row->attribute : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.attributes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('attribute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.attributes.create');
    }

    public function store(StoreAttributeRequest $request)
    {
        $attribute = Attribute::create($request->all());

        return redirect()->route('admin.attributes.index');
    }

    public function edit(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->all());

        return redirect()->route('admin.attributes.index');
    }

    public function show(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.attributes.show', compact('attribute'));
    }

    public function destroy(Attribute $attribute)
    {
        abort_if(Gate::denies('attribute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attribute->delete();

        return back();
    }

    public function massDestroy(MassDestroyAttributeRequest $request)
    {
        Attribute::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
