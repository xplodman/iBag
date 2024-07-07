<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BinReading extends Model
{
    use SoftDeletes;

    protected $fillable = ['bin_id', 'sensor_readings'];

    protected $casts = [
        'sensor_readings' => 'array',
    ];

    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }
}
