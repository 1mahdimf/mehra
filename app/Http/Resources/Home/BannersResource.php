<?php

namespace App\Http\Resources\Home;

use App\Enums\AnnouncementPosition;
use App\Helpers\Helpers;
use App\Http\Resources\MehraResource;


class BannersResource extends MehraResource
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
            'url'=> $this->url,
            '_blank'=> (bool)$this->_blank,
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

    public function __construct($resource)
    {
        if($resource->position==AnnouncementPosition::EVERYWHERE || $resource->position==AnnouncementPosition::HOME)
           parent::__construct($resource);
    }
}
