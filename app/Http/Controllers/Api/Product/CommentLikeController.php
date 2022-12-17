<?php

namespace App\Http\Controllers\Api\Product;

use App\Enums\AttributeType;
use App\Exceptions\MehraApiException;
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

class CommentLikeController extends Controller {

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Comment $comment)
    {
        try {
            $comment->likes()->firstOrCreate(['user_id'=>$this->user_id]);
        } catch (\Exception $exception){
            return $this->errorResponse('خطا در انجام عملیات');
        }
        return $this->successResponse('عملیات با موفقیت انجام شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->likes()->delete(['user_id'=>$this->user_id]);

        } catch (MehraApiException $exception){
            return $this->errorResponse('خطا در انجام عملیات');
        }

        return $this->successResponse('عملیات با موفقیت انجام شد');

    }

}
