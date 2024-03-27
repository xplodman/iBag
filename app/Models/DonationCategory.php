<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DonationCategory extends Pivot
{
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->where('donation_category', true);
    }
}
