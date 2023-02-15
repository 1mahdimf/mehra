<?php

namespace App\Models;

use App\Services\Media\HasMediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Announcement extends Model implements HasMedia
{
    use HasFactory, HasMediaTrait;

    protected $with = ['medias'];

    public static function getValidCollections(): array
    {
        return [
            'image',
        ];
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $conversion = $this->addMediaConversion('thumbnail');

        $crop = $media->getCustomProperty('crop');

        if (!empty($crop)) {
            $conversion->manualCrop($crop['width'], $crop['height'], $crop['left'], $crop['top']);
        }

        $conversion->nonQueued()->performOnCollections('image');
    }
}
