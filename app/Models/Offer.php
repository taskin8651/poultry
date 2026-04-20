<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'min_amount',
        'reward_type',
        'reward_value',
        'start_date',
        'end_date',
        'status'
    ];

    // 🔥 Image Collection
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('offer_image')->singleFile();
    }
}