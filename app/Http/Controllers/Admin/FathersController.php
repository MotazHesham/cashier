<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFatherRequest;
use App\Http\Requests\StoreFatherRequest;
use App\Http\Requests\UpdateFatherRequest;
use App\Models\Father;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Spatie\MediaLibrary\Models\Media;
use Alert;

class FathersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('father_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fathers = Father::with(['user'])->get();

        return view('admin.fathers.index', compact('fathers'));
    }

    public function create()
    {
        abort_if(Gate::denies('father_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.fathers.create', compact('users'));
    }

    public function store(StoreFatherRequest $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'father',
            'phone' => $request->phone,
        ]);

        foreach ($request->input('identity', []) as $file) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('identity');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }
        if ($request->input('photo', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }
        $father = Father::create([
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.fathers.index');
    }

    public function edit(Father $father)
    {
        abort_if(Gate::denies('father_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $father->load('user');
        $user = $father->user;
        return view('admin.fathers.edit', compact('father', 'users','user'));
    }

    public function update(UpdateFatherRequest $request, Father $father)
    {
        $user = User::find($request->user_id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password == null ? $user->password : bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        $media = $user->identity->pluck('file_name')->toArray();
        foreach ($request->input('identity', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $user->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('identity');
            }
        }
        if ($request->input('photo', false)) {
            if (!$user->photo || $request->input('photo') !== $user->photo->file_name) {
                if ($user->photo) {
                    $user->photo->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($user->photo) {
            $user->photo->delete();
        }
        return redirect()->route('admin.fathers.index');
    }

    public function show(Father $father)
    {
        abort_if(Gate::denies('father_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $father->load('user.transactions');
        $user = $father->user;
        $transactions = $user->transactions()->orderBy('created_at','desc')->paginate(5);

        return view('admin.fathers.show', compact('father','user','transactions'));
    }

    public function destroy(Father $father)
    {
        abort_if(Gate::denies('father_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($father->user->current_balance() > 0){
          Alert::error('لا يمكن المسح');
          return back();
        }
        $father->user()->delete();
        $father->delete();

        Alert::success('تم الحذف بنجاح');
        return back();
    }

    public function storeCKEditorImages(Request $request)
    {

        $model         = new User();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
