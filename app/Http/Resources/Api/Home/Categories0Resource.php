<?php

namespace App\Http\Resources\Api\Home;

use App\Http\Resources\Api\CategoryResourceCollection;

class Categories0Resource extends HomeResource
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
            'title'=> $this->title,
            'items'=>[
                'id'=> $this->id,
                'title'=> $this->title,
                'slug'=> $this->slug,
                'category_template'=> $this->whenLoaded('category_template'),
                'image'=> $this->whenLoaded('medias',function (){
                    if($this->hasMedia('image'))
                        return $this->getFirstMediaUrl('image');
                }),
            ]
        ];
    }


    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => true
        ];
    }
}
