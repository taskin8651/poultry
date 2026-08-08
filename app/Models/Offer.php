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

        // Who it applies to
        'applies_to', // all | egg | hen

        // Offer Condition
        'condition_type', // price | kg | piece | qty
        'condition_value',

        // Reward (always credited to the customer's wallet)
        'reward_kind',  // fixed | percent
        'reward_value',

        // Dates
        'start_date',
        'end_date',

        // Status
        'status',
    ];

    protected $casts = [
        'condition_value' => 'decimal:2',
        'reward_value'    => 'decimal:2',
        'start_date'      => 'date',
        'end_date'        => 'date',
        'status'          => 'boolean',
    ];

    /**
     * Offer Image
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('offer_image')->singleFile();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    public function appliesToType(string $type): bool
    {
        return $this->applies_to === 'all' || $this->applies_to === $type;
    }
}