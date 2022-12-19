<?php

namespace App\Http\Controllers\Api;

use App\Services\Media\Media;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiResponse;
    public int $perPage = 12;
    public $user_id = null;

    public function __construct()
    {
        if(auth()->guard('sanctum')->check())
            $this->user_id = auth()->guard('sanctum')->id();
    }

    public function callAction($method, $parameters)
    {
        if($method=='index'){
            $this->perPage = 12;
            if(isset(request()->query()['per_page'])){
                $this->perPage = (int)request()->query()['per_page'];
                if((int)request()->query()['per_page']>20)
                    $this->perPage = 12;
            }
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function uploadMedia($model,$collectionName='gallery',$mediaKey='media',$removeOld=false)
    {
        if(request()->hasFile($mediaKey)){
            if(!$removeOld && optional($model->getMediaCollection($mediaKey))->singleFile)
               $removeOld = true;

            if($removeOld){
                $model->clearMediaCollection($mediaKey);
            }
            $mediaList = request()->file($mediaKey);
            if(!is_array($mediaList)){
                $mediaList = [request()->file($mediaKey)];
            }
            foreach ($mediaList as $media){
                $model
                    ->addMedia($media) //starting method
                    ->withResponsiveImages()
                    ->toMediaCollection($collectionName);
            }
        }
    }


}
