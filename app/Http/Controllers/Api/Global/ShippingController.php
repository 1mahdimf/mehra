<?php

namespace App\Http\Controllers\Api\Global;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\AddToCartRequest;
use App\Http\Requests\Api\RemoveFromCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Models\City;
use App\Models\State;
use App\Services\CartService;

class ShippingController extends Controller {


    /*
     * Get States
     */
    public function getStates()
    {
       return State::query()->get();
    }

    /*
     * Get Cities By State
     */
    public function getCities(State $state)
    {
        return $state->cities()->get();
    }
}
