<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'category_id',

        'type',        // egg / hen
        'sale_type',   // tray / piece / weight

        'base_price',  // current market price
        'stock',

        'description',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function bulkPrices()
    {
        return $this->hasMany(BulkPrice::class)->orderBy('min_qty');
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 PRICE ENGINE (CORE LOGIC)
    |--------------------------------------------------------------------------
    */

    public function getPrice($qty)
    {
        $price = $this->base_price;

        foreach ($this->bulkPrices as $bulk) {
            if ($qty >= $bulk->min_qty) {
                $price = $bulk->price;
            }
        }

        return $price;
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA
    |--------------------------------------------------------------------------
    */

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_thumbnail')->singleFile();
        $this->addMediaCollection('product_gallery');
    }
}