<?php

namespace App\Http\Controllers\Api\Product;

use App\Enums\AttributeType;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Product\StoreCommentRequest;
use App\Http\Resources\CommentResourceCollection;
use App\Http\Resources\ProductCommentResource;
use App\Http\Resources\ProductCommentResourceCollection;
use App\Http\Resources\ProductResource;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CommentController extends Controller {

    public function index(Request $request, Product $product)
    {
        $product = $product->load(['rates','comments'=> function($c){
                    $c->with(['points','likes','media']);
                }
            ]);
        return ProductCommentResource::make($product);
    }

    public function store(StoreCommentRequest $request, Product $product)
    {
        try {
            $comment = auth()->user()->comments()->create($request->validated());
        } catch (\Exception $exception){
            return $this->errorResponse('خطا در انجام عملیات');
        }
        return $this->successResponse('عملیات با موفقیت انجام شد');
    }
}
