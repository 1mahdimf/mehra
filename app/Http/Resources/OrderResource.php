<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;


class OrderResource extends MehraResource
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
            'date'=> jdate($this->date)->format('Y-m-d'),
            'total_items'=> $this->whenLoaded('items',function (){
                return count($this->items) ? $this->items->sum('quantity') : 0;
            }),
            'total_price'=> $this->total_price,
            'currency'=> 'تومان',
            'total_price_formatted'=> Helpers::toman($this->total_price),
            'shipping_price'=> 0,
            'is_shipping_free'=> true,
            'items'=> $this->whenLoaded('items',function (){
                return OrderItemResource::collection($this->items->load(['line_item'=>function($line_item){
                        $line_item->with(['producer','medias']);
                    }]
                ));
            }),
            'user'=> $this->whenLoaded('user',function (){
                return UserResource::make($this->user->load('addresses'));
            })
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
