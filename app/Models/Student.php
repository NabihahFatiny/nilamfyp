<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'id_student';

    protected $fillable = ['name', 'email', 'password', 'dob', 'class', 'address', 'photo'];

    // Passwords are stored as plain text (not hashed)
    // Mutator ensures password is never hashed
    public function setPasswordAttribute($value)
    {
        // Store password as plain text - do not hash
        $this->attributes['password'] = $value;
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'id_student');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'id_student');
    }

    public function summaries()
    {
        return $this->hasMany(Summary::class, 'id_student', 'id_student');
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class, 'id_student', 'id_student');
    }
}
