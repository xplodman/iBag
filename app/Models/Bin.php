<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bin extends Model
{
    use SoftDeletes;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token = \Str::random(120);
        });

        static::created(function ($model) {
            $model->token = hash('sha256', $model->id . $model->user_id . \Str::random(32));
            $model->save();
        });
    }

    protected $fillable = [
        'user_id',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function binReadings()
    {
        return $this->hasMany(BinReading::class);
    }

    public function totalPayments(): int
    {
        return $this->binReadings->count();
    }
}
