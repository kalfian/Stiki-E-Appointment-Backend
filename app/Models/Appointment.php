<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function lecture() {
        return $this->belongsTo(User::class, 'lecture_id');
    }

    public function lecture2() {
        return $this->belongsTo(User::class, 'lecture2_id');
    }

    public function activity() {
        return $this->belongsTo(Activity::class);
    }
}
