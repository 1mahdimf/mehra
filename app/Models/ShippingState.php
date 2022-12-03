<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingState extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
//    use HasFactory;

    public function cities(): HasMany
    {
        return $this->hasMany(ShippingCity::class);
    }
}
