<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $primaryKey = 'id_challenge';

    protected $fillable = ['id_student', 'id_book', 'date_finished', 'title', 'rating'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student', 'id_student');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'id_book', 'id_book');
    }
}
