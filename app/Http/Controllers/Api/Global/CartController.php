<?php

namespace App\Http\Controllers\Api\Global;

use App\Enums\ProductStructure;
use App\Exceptions\Api\Cart\AddItemException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\RemoveFromCartRequest;
use App\Http\Requests\Api\Cart\SelectAddressRequest;
use App\Http\Requests\Api\Cart\SyncCartRequest;
use App\Http\Resources\Api\CartEmptyResource;
use App\Http\Resources\Api\CartResource;
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
        $cart = $this->cart->getCart();
        if(!is_null($cart) && $cart->exists())
            return new CartResource($cart);
        else
            return new CartEmptyResource([]);
    }

    /*
     * Sync Cart From Local Storage
     */
    public function syncCart(SyncCartRequest $request)
    {
        $products = $request->products;
        foreach ($products as $product) {
            $this->cart->addToCart(
                $product->id,
                $product->sync_quantity,
            );
        }
        $cart = $this->cart->getCart();
        return new CartResource($cart);
    }

    /*
     * Add Item To Cart With Cart Service
     * @response CartResource $cart
     */
    public function addItem(AddToCartRequest $request)
    {
        $product_id = $request->validated('id');
        $is_virtual = $request->input('is_virtual')==1;
        $quantity = $request->validated('quantity');
        try {
            return new CartResource($this->cart->addToCart($product_id,$quantity,$is_virtual));
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
