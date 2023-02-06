<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    public $table = 'students';

    public const GRADE_SELECT = [
        "G2022" => "G2022",
        "Grade 1" => "Grade 1",
        "Grade 10" => "Grade 10",
        "Grade 11" => "Grade 11",
        "Grade 12" => "Grade 12",
        "Grade 2" => "Grade 2",
        "Grade 3" => "Grade 3",
        "Grade 4" => "Grade 4",
        "Grade 5" => "Grade 5",
        "Grade 6" => "Grade 6",
        "Grade 7" => "Grade 7",
        "Grade 8" => "Grade 8",
        "Grade 9" => "Grade 9",
        "KG1" => "KG1",
        "KG2" => "KG2",
        "Pre K" => "Pre K",
    ];
    public const CLASS_SELECT = [
        "1001" => "1001",
        "1002" => "1002",
        "1003" => "1003",
        "101" => "101",
        "102" => "102",
        "103" => "103",
        "1101" => "1101",
        "1102" => "1102",
        "1103" => "1103",
        "1201" => "1201",
        "1202" => "1202",
        "1203" => "1203",
        "1204" => "1204",
        "201" => "201",
        "202" => "202",
        "203" => "203",
        "301" => "301",
        "302" => "302",
        "303" => "303",
        "401" => "401",
        "402" => "402",
        "403" => "403",
        "501" => "501",
        "502" => "502",
        "503" => "503",
        "601" => "601",
        "602" => "602",
        "603" => "603",
        "701" => "701",
        "702" => "702",
        "703" => "703",
        "801" => "801",
        "802" => "802",
        "901" => "901",
        "902" => "902",
        "903" => "903",
        "G2022-1" => "G2022-1",
        "G2022-2" => "G2022-2",
        "k101" => "k101",
        "k201" => "k201",
        "k202" => "k202",
        "k301" => "k301",
        "k302" => "k302",
        "k303" => "k303",
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'father_id',
        'grade',
        'class',
        'father_email',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function father()
    {
        return $this->belongsTo(Father::class, 'father_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
