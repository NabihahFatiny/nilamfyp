<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'id_teacher';

    protected $fillable = ['name', 'email', 'password', 'address', 'photo'];

    // Passwords are stored as plain text (not hashed)
    // Mutator ensures password is never hashed
    public function setPasswordAttribute($value)
    {
        // Store password as plain text - do not hash
        $this->attributes['password'] = $value;
    }
}
