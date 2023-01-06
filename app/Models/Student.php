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
        'pre-k'   => 'Pre K',
        'k-1'   => 'K1',
        'k-2'   => 'K2',
        '1'   => 'Grade 1',
        '2'  => 'Grade 2',
        '3' => 'Grade 3',
        '4' => 'Grade 4',
        '5'  => 'Grade 5',
        '6' => 'Grade 6',
        '7' => 'Grade 7',
        '8' => 'Grade 8',
        '9' => 'Grade 9',
        '10' => 'Grade 10',
        '11' => 'Grade 11',
        '12' => 'Grade 12',
    ];
    public const CLASS_SELECT = [
        '01'   => 'Class 01',
        '02'  => 'Class 02',
        '03' => 'Class 03',
        '04' => 'Class 04',
        '05' => 'Class 05',
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
