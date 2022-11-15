<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{

    protected $appends = ['name'];
    protected $with = ['attribute'];

    public function attribute(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function getNameAttribute() : string
    {
        return $this->attribute->name;
    }
}
