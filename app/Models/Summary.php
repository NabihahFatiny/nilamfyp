<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $primaryKey = 'id_summary';
    protected $fillable = ['id_student', 'id_book', 'date', 'book', 'summary', 'comment'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student', 'id_student');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'id_book', 'id_book');
    }
}
