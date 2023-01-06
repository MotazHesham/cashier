<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
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
use Auth;

class StudentsController extends Controller
{
    public function index()
    {

        $father = Father::where('user_id',Auth::id())->first();
        $students = Student::with(['user', 'father'])->where('father_id',$father->id)->get();

        return view('father.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load('user', 'father');
        $user = $student->user;
        $transactions = $user->transactions()->orderBy('created_at','desc')->paginate(5);
        return view('father.students.show', compact('student','user','transactions'));
    }
}
