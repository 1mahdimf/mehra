<?php

namespace App\Services\Media;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\InteractsWithMedia as BasicHasMedia;

trait HasMediaTrait
{
    use BasicHasMedia;

    public function main_image()
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', 'main_image');
    }

    public function mediaVerified(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->whereNotNull('verified_at');
    }

    public function mediaPdfVerified(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')->whereNotNull('pdf_verified_at');
    }

    public static function isValidMediaCollection(string $collectionName): bool
    {
        return in_array($collectionName, static::getValidCollections());
    }

    protected static function getValidCollections(): array
    {
        return [];
    }
}
