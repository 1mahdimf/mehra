<?php

namespace App\Http\Resources\Api;

use App\Enums\CollectionType;
use App\Enums\ProductStructure;


class CollectionResource extends MehraResource
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
            'id'=> $this->id,
            'items'=> $this->whenLoaded('items',function(){
                return CollectionProductResource::collection($this->items);
            }),
            'title'=> $this->title,
            'count'=> $this->items_count,
            'image'=> $this->whenLoaded('medias',function (){
                if($this->hasMedia('image'))
                    return $this->getFirstMediaUrl('image');
            }),
        ];
    }
}
