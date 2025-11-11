<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
   public function student() {
    return $this->belongsTo(Student::class);
}

public function teacher() {
    return $this->belongsTo(Teacher::class);
}

public function class() {
    return $this->belongsTo(Classes::class);
}

}
