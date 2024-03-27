<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasMedia
{
    use InteractsWithMedia;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'provider',
        'provider_id',
        'otp',
        'is_provider',
        'mobile',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'profile_image',
        'total_orders_points',
        'total_order_items_kg',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin|moderator');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function profileImage(): Attribute
    {
        return Attribute::get(function () {
            $mediaItems = $this->getMedia();

            return isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : '';
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function totalOrdersPoints(): Attribute
    {
        return Attribute::get(function () {
            return (int) $this->orders()->where('confirmed', true)->sum('points');
        });
    }

    public function totalOrderItemsKg(): Attribute
    {
        return Attribute::get(function () {
            return (int) $this->orders()->where('confirmed', true)->with('orderCategories')->get()->sum(function ($order) {
                return $order->orderCategories->sum('estimated_kg');
            });
        });
    }
}
