<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends MehraResource
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
            'address'=> $this->address,
            'full_address'=> $this->full_address,
            'state_id'=> $this->state_id,
            'state'=> $this->state_title,
            'city_id'=> $this->city_id,
            'city'=> $this->city_title,
            'postal_code'=> $this->postal_code,
            'mobile'=> $this->mobile,
            'phone'=> $this->phone,
            'last_name'=> $this->last_name,
            'first_name'=> $this->first_name,
        ];
    }
}
