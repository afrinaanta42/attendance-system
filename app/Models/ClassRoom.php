<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'classes';
    protected $fillable = ['class_name', 'section', 'subject'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'class_id');
    }
}
