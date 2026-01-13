<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $primaryKey = 'id_book';

    protected $fillable = ['id_student', 'name', 'author', 'edition', 'date_reading', 'date_finished'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

    public function summaries()
    {
        return $this->hasMany(Summary::class, 'id_book', 'id_book');
    }

    public function challenge()
    {
        return $this->hasOne(Challenge::class, 'id_book', 'id_book');
    }
}
