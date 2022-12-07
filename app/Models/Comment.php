<?php

namespace App\Models;

use App\Services\Media\HasMediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Markable;
use Maize\Markable\Models\Like;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;

class Comment extends Model implements HasMedia
{
    use LogsActivity, HasMediaTrait;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "This comment has been {$eventName}");
//            ->dontLogIfAttributesChangedOnly([]);
    }

    public static function getValidCollections(): array
    {
        return [
            'main_image',
        ];
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->singleFile();
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $conversion = $this->addMediaConversion('thumbnail');

        $crop = $media->getCustomProperty('crop');

        if (!empty($crop)) {
            $conversion->manualCrop($crop['width'], $crop['height'], $crop['left'], $crop['top']);
        }

        $conversion->nonQueued()->performOnCollections('main_image');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function points()
    {
        return $this->hasMany(CommentPoint::class);
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }
}
