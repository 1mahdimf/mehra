<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingCity extends Model
{
    public $timestamps = false;
    protected $guarded = [];
//    use HasFactory;

    public function state(): BelongsTo
    {
        return $this->belongsTo(ShippingState::class);
    }
}
