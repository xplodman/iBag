<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    public static array $allowedFilters = [
        'category_donation'
    ];

    protected $fillable = [
        'name',
        'category_donation',
    ];

    protected $casts = [
        'category_donation' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'image',
    ];

    public function donations()
    {
        return $this->belongsToMany(Donation::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function image(): Attribute
    {
        return Attribute::get(function () {
            $mediaItems = $this->getMedia();

            return isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : '';
        });
    }
}
