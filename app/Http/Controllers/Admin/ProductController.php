<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\AttributeProduct;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use DB;

class ProductController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function attribute_combination(Request $request){
        $combinations = array();

        if($request->has('attribute_num')){
            foreach ($request->attribute_num as $key => $num) {
                $name = 'attributes_options_'.$num;
                $my_str = implode('', $request[$name]);
                array_push($combinations, explode(',', $my_str));
            }
        }

        if($request->has('product_id')){
            $product = Product::find($request->product_id);
            $product->load('attributeProduct');
        }else{
            $product = null;
        }
        return view('admin.products.partials.attribute_combination', compact('combinations','product'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Product::with(['category', 'attributeProduct'])->select(sprintf('%s.*', (new Product())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_show';
                $editGate = 'product_edit';
                $deleteGate = 'product_delete';
                $crudRoutePart = 'products';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Product::STATUS_SELECT[$row->status] : '';
            });
            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });

            $table->editColumn('photo', function ($row) {
                if ($photo = $row->photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });

            $table->rawColumns(['actions', 'placeholder', 'category', 'photo']);

            return $table->make(true);
        }

        return view('admin.products.index');
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attributes = Attribute::pluck('attribute', 'id');

        return view('admin.products.create', compact('attributes', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        try{
            DB::beginTransaction();

            $validated_request = $request->all();

            $attributes_options = array();

            if($request->has('attribute_num')){
                foreach ($validated_request['attribute_num'] as $key => $num) {
                    $str = 'attributes_options_'.$num;

                    $item['attribute_id'] = $num;
                    $item['values'] = explode(',', implode('|', $request[$str]));

                    array_push($attributes_options, $item);
                }
            }

            if (!empty($request->attribute_num)){
                $validated_request['attributes'] = json_encode($validated_request['attribute_num']);
            }else{
                $validated_request['attributes'] = json_encode(array());
            }

            $validated_request['attributes_options'] = json_encode($attributes_options);

            $product = Product::create($validated_request);

            if($request->has('attribute_num')){
                foreach ($validated_request['attribute_num'] as $key => $num) {

                    $str = explode(',', implode('|', $request['attributes_options_'.$num]));

                    foreach($str as $variant){
                        $attribute_product = AttributeProduct::create([
                            'attribute_id' => $num,
                            'product_id' => $product->id,
                            'variant' => $variant,
                            'price' => $validated_request['extra_price_'.$variant],
                        ]);
                    }
                }
            }

            if ($request->input('photo', false)) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }

            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $product->id]);
            }

            DB::commit();
            Alert::success('تم بنجاح', 'تم إضافة المنتج بنجاح ');
            return redirect()->route('admin.products.index');
        }catch(\Exception $ex){
            DB::rollBack();
            Alert::error('حدث خطأ','برجاء أدخال الحقول بطريقة صحيحة');
            return redirect()->route('admin.products.index');
        }
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attributes = Attribute::pluck('attribute', 'id');

        $product->load('category', 'attributeProduct');

        return view('admin.products.edit', compact('attributes', 'categories', 'product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try{
            DB::beginTransaction();

                $validated_request = $request->all();

                $attributes_options = array();

                if($request->has('attribute_num')){
                    foreach ($validated_request['attribute_num'] as $key => $num) {
                        $str = 'attributes_options_'.$num;

                        $item['attribute_id'] = $num;
                        $item['values'] = explode(',', implode('|', $request[$str]));

                        array_push($attributes_options, $item);
                    }
                }

                if (!empty($request->attribute_num)){
                    $validated_request['attributes'] = json_encode($validated_request['attribute_num']);
                }else{
                    $validated_request['attributes'] = json_encode(array());
                }

                $validated_request['attributes_options'] = json_encode($attributes_options);

                $product->update($validated_request);

                if($request->has('attribute_num')){

                    foreach($product->attributeProduct as $row){
                        $row->delete();
                    }

                    foreach ($validated_request['attribute_num'] as $key => $num) {

                        $str = explode(',', implode('|', $request['attributes_options_'.$num]));

                        foreach($str as $variant){
                            $attribute_product = AttributeProduct::create([
                                'attribute_id' => $num,
                                'product_id' => $product->id,
                                'variant' => $variant,
                                'price' => $validated_request['extra_price_'.$variant],
                            ]);
                        }
                    }
                }


                if ($request->input('photo', false)) {
                    if (!$product->photo || $request->input('photo') !== $product->photo->file_name) {
                        if ($product->photo) {
                            $product->photo->delete();
                        }
                        $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
                    }
                } elseif ($product->photo) {
                    $product->photo->delete();
                }

            Alert::success('تم بنجاح', 'تم تعديل بيانات المنتج بنجاح ');
            DB::commit();
            return redirect()->route('admin.products.index');
        }catch(\Exception $ex){
            DB::rollBack();
            Alert::error('حدث خطأ','برجاء أدخال الحقول بطريقة صحيحة');
            return redirect()->route('admin.products.index');
        }
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load('category', 'attributeProduct');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        Alert::success('تم بنجاح', 'تم  حذف المنتج بنجاح ');
        return 1;
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        Product::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
