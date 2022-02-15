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
        if(!$generalSetting){
            $generalSetting = GeneralSetting::create([
                'website_title' => 'A.G.N RESTAURANT',
                'phone_1' => '01001001010',
                'phone_2' => '025644444',
                'address' => 'A.G.N International School',
            ]);
        } 

        return view('admin.generalSettings.edit', compact('generalSetting'));
    } 

    public function update(UpdateGeneralSettingRequest $request, GeneralSetting $generalSetting)
    {
        $generalSetting->update($request->all());

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