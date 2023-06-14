<?php

namespace App\Http\Resources\Api;

use App\Helpers\Helpers;
use App\Models\Discount;

class CartResource extends MehraResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        dd($this->items);
        $discount = $this->discount;
        $discountDescription = $discount ? $discount->getDescription() : null;
        $discountName = $discount ? $discount->getName() : null;

        return [
            'items' => $this->whenLoaded('items', function () {
                return new CartItemResourceCollection($this->items);
            }),
            'total_items' => $this->items && count($this->items) ? $this->items->sum('quantity') : 0,
            'total_price' => $this->total_price,
            'total_main_price' => $this->total_final_price,
            'total_price_formatted' => Helpers::toman($this->total_final_price),
            'discount' => (($this->total_final_price/$this->total_price) * 100)."%",
            'discount_name' => $discountName,
            'discount_description' => $discountDescription,
            'currency' => 'تومان',
            'profit' => $this->profit,
            'profit_formatted' => Helpers::toman($this->profit),
            'shipping_price' => $this->total_shipping_price,
            'shipping_price_formatted' => Helpers::toman($this->total_shipping_price),
            'is_shipping_free' => false,
            'address' => $this->whenLoaded('address', function () {
                return UserAddressResource::make($this->address);
            }),
            'address_id' => $this->address_id,
            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),
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
