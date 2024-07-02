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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'total_estimated_kg',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderCategories(): HasMany
    {
        return $this->hasMany(OrderCategory::class);
    }

    /**
     * Get the total estimated KGs for each orderCategories.
     */
    protected function getTotalEstimatedKgAttribute()
    {
        return $this->orderCategories->sum('estimated_kg');
    }
}
