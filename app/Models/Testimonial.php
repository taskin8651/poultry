<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'position',
        'message',
        'rating',
        'status'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('testimonial_image')->singleFile();
    }
}