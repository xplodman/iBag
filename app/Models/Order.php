<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public static array $allowedSorts = [
        'created_at'
    ];

    protected $fillable = [
        'address',
        'location',
        'date',
        'interval',
        'remind_me',
        'user_id',
        'notes',
        'confirmed',
        'points',
    ];

    protected $casts = [
        'remind_me' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderCategories(): HasMany
    {
        return $this->hasMany(OrderCategory::class);
    }
}
