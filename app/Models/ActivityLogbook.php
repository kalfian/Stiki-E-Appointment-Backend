<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLogbook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_id',
        'user_id',
        'lecture_comment',
        'problem',
        'logbook_proof',
        'date',
        'description',
        'created_at',
        'updated_at',
    ];
}
