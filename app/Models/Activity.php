<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Spatie\Image\Manipulations;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Activity extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    public function banner() {
        return $this->morphOne(Media::class, 'model');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
        ->fit(Manipulations::FIT_CROP, 200, 200)
        ->nonQueued();
    }

    public function participants() {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function students() {
        return $this->participants()->where('is_lecturer', '=', false);
    }

    public function lectures() {
        return $this->participants()->where('is_lecturer', '=', true);
    }
}
