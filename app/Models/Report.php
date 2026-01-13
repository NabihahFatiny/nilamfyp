<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $primaryKey = 'id_report';

    protected $fillable = ['id_student', 'reading_level'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student', 'id_student');
    }
}
