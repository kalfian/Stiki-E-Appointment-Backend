<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityParticipant extends Model
{
    use HasFactory, SoftDeletes;

    public function activity() {
        return $this->belongsTo(Activity::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
