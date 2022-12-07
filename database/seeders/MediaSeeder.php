<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Product;
use App\Services\Media\Media;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\MediaLibrary\MediaCollections\File;
use Faker\Factory as Faker;

class MediaSeeder extends Seeder
{
    public int $galleryCount = 3;
    private function deleteModels($modelName)
    {
        foreach (Media::query()->where('model_type',$modelName)->get() as $item) {
            $item->delete();
        }
    }

    private function deleteDir($modelName)
    {
        if(Storage::disk('media')->exists($modelName)) {
            Storage::disk('media')->deleteDirectory($modelName);
            if (Storage::disk('media')->exists($modelName)) {
                self::deleteDir($modelName);
            }
        }
    }

    private function getRandomMedia($media)
    {
        $media_rand = array_rand($media);
        $array = explode('.', $media[$media_rand]);
        if ($this->fileExists($media, $media_rand)) {
            return ['media' => $media_rand, 'ext' => end($array)];
        }
    }

    private function fileExists($media,$random)
    {
        return \Illuminate\Support\Facades\File::exists(public_path('media/') . $media[$random]);
    }

    private function findMp4($media,$modelItem,$collectionName='gallery')
    {
        $mediaRandom = $this->getRandomMedia($media);
        if(
            $mediaRandom['ext'] == 'mp4'
        ){
            $modelItem->addMediaFromUrl(URL::to('media/' . $media[$mediaRandom['media']]))
                    ->toMediaCollection($collectionName);
        }
    }

    private function findJpg($media,$modelItem,$collectionName='gallery')
    {
        $mediaRandom = $this->getRandomMedia($media);
        if($mediaRandom['ext'] == 'jpg'){
            $modelItem->addMediaFromUrl(URL::to('media/' . $media[$mediaRandom['media']]))
                ->toMediaCollection($collectionName);
        }
    }

    private function generateMedia($media,$modelItem)
    {
        foreach ($modelItem->getValidCollections() as $collectionName) {
            if($collectionName!=='gallery') {
                $this->findJpg($media, $modelItem, $collectionName);
            } else {
                for ($i = 0; $i < $this->galleryCount; $i++) {
                    $this->findJpg($media,$modelItem);
                    $this->findMp4($media,$modelItem);
                }
            }
        }

    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // models to generate
        $models = [
            'book',
            'award',
            'category',
            'collection',
            'creator',
            'producer',
            'user'
        ];
        foreach ($models as $modelName) {
            // delete folder and media
            $this->deleteDir($modelName);
            $this->deleteModels($modelName);

            // get media list
            $mediaList = Storage::disk('media')->allFiles();
            // get model from model name
            $model = app("App\Models\\".Str::ucfirst($modelName));
            // generate media
            foreach ($model->query()->get() as $modelItem){
                $this->generateMedia($mediaList,$modelItem);
            }
        }
    }
}
