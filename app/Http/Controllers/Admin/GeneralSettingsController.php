<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateGeneralSettingRequest;
use App\Models\GeneralSetting;
use App\Models\IncomeCategory;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Alert;

class GeneralSettingsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('general_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $generalSetting = GeneralSetting::first();
        $cashier_printer = json_decode($generalSetting->cashier_printer);
        $kitchen_printer = json_decode($generalSetting->kitchen_printer);
        if(!$generalSetting){
            $generalSetting = GeneralSetting::create([
                'website_title' => 'A.G.N RESTAURANT',
                'phone_1' => '01001001010',
                'phone_2' => '025644444',
                'address' => 'A.G.N International School',
            ]);
        }

        return view('admin.generalSettings.edit', compact('generalSetting','cashier_printer','kitchen_printer'));
    }

    public function update(UpdateGeneralSettingRequest $request, GeneralSetting $generalSetting)
    {
        $cashier_printer = array();
        $cashier_printer['printer'] = $request->cashier_printer;
        $cashier_printer['print_times'] = $request->print_times_cashier;

        $kitchen_printer = array();
        $kitchen_printer['printer'] = $request->kitchen_printer;
        $kitchen_printer['print_times'] = $request->print_times_kitchen;

        $validated_request = $request->all();
        $validated_request['cashier_printer'] = json_encode($cashier_printer);
        $validated_request['kitchen_printer'] = json_encode($kitchen_printer);

        $generalSetting->update($validated_request);

        if ($request->input('logo', false)) {
            if (!$generalSetting->logo || $request->input('logo') !== $generalSetting->logo->file_name) {
                if ($generalSetting->logo) {
                    $generalSetting->logo->delete();
                }
                $generalSetting->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($generalSetting->logo) {
            $generalSetting->logo->delete();
        }
        Alert::success('تم بنجاح', 'تم التعديل بنجاح ');
        return redirect()->route('admin.general-settings.index');
    }
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('general_setting_create') && Gate::denies('general_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new GeneralSetting();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
