<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Student extends Model
{
    use HasFactory;
    protected $fillable = ['roll_number', 'dob', 'gender', 'class_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
