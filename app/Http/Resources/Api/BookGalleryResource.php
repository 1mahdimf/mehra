<?php

namespace App\Http\Resources\Api;

use App\Enums\ProductType;
use App\Helpers\Helpers;


class BookGalleryResource extends MehraResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'url'=> $this->original_url,
            'type'=> $this->mime_type
        ];
    }
}
