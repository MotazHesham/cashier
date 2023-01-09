<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStudentRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Father;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Alert;
use App\Imports\StudentsImport;
use App\Models\GeneralSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentsController extends Controller
{

    use CsvImportTrait;

    public function upload_students(Request $request){

        $now_time = time();

        $path = $request->excel_file->store('uploads/excelfiles/'.$now_time);

        $sheets = (new StudentsImport)->toCollection($request->excel_file);

        foreach($sheets[0] as $key => $row){
            if($key != 0){
            }
        }

        return $sheets;
    }

    public function print($id){
        $setting = GeneralSetting::first();
        $student = Student::findOrFail($id);
        $user = $student->user;

        $pdf = Pdf::loadView('admin.students.card_info',compact('user','student','setting'));
        return $pdf->download($user->name . '.pdf');
    }

    public function index()
    {
        abort_if(Gate::denies('student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $students = Student::with(['user', 'father'])->get();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        abort_if(Gate::denies('student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fathers = Father::with('user')->get()->pluck('user.name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('admin.students.create', compact('fathers'));
    }

    public function store(StoreStudentRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'student',
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
        $student = Student::create([
            'user_id' => $user->id,
            'father_id' => $request->father_id,
            'grade' => $request->grade,
            'class' => $request->class,
        ]);

        return redirect()->route('admin.students.index');
    }

    public function edit(Student $student)
    {
        abort_if(Gate::denies('student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $fathers = Father::with('user')->get()->pluck('user.name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $student->load('user', 'father');

        $user = $student->user;

        return view('admin.students.edit', compact('fathers', 'student', 'user'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
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
        $student->update([
            'father_id' => $request->father_id,
            'grade' => $request->grade,
            'class' => $request->class,
        ]);

        return redirect()->route('admin.students.index');
    }

    public function show(Student $student)
    {
        abort_if(Gate::denies('student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $student->load('user', 'father');
        $user = $student->user;
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->paginate(5);
        $setting = GeneralSetting::first();
        return view('admin.students.show', compact('student', 'user', 'transactions','setting'));
    }

    public function destroy(Student $student)
    {
        abort_if(Gate::denies('student_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($student->user->orders || $student->user->order_products()->get()->isNotEmpty() || $student->user->current_balance() > 0) {
            Alert::error('لا يمكن المسح');
            return back();
        }
        $student->user()->delete();
        $student->delete();

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
