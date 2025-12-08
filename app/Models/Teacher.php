<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'class_id', 'subject'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom() 
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
