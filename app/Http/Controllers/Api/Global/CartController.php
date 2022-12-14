<?php

namespace App\Http\Controllers\Api\Global;

use App\Exceptions\Api\Cart\AddItemException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\RemoveFromCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller {

    /*
     * Cart Service Inject
     */
    protected CartService $cart;
    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /*
     * Get Cart From Cart Service With Cart Resource
     */
    public function getCart()
    {
        return new CartResource($this->cart->getCart());
    }

    /*
     * Add Item To Cart With Cart Service
     * @response CartResource $cart
     */
    public function addItem(AddToCartRequest $request)
    {
        $product_id = $request->validated('id');
        $quantity = $request->validated('quantity');
        try {
            return new CartResource($this->cart->addToCart($product_id,$quantity));
        }
        catch (AddItemException $exception){}
    }

    /*
     * Remove Item From Cart With Cart Service
     * @response CartResource $cart
     */
    public function removeItem(RemoveFromCartRequest $request)
    {
        $product_id = $request->validated('id');
        $quantity = $request->validated('quantity');
        try {
            return new CartResource($this->cart->removeFromCart($product_id,$quantity));
        }
        catch (\Exception $exception){
            return $this->errorResponse('خطا در عملیات');
        }
    }
}
