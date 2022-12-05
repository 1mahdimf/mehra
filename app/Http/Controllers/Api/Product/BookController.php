<?php

namespace App\Http\Controllers\Api\Product;

use App\Builder\Filters\FiltersVolumeTitle;
use App\Builder\Includes\AggregateInclude;
use App\Enums\AttributeType;
use App\Enums\ProducerType;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookResourceCollection;
use App\Http\Resources\CreatorResourceCollection;
use App\Http\Resources\ProductResource;
use App\Models\Book;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

class BookController extends Controller {

    public function index(Request $request)
    {
        // global input search
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                \Illuminate\Database\Eloquent\Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('title', 'LIKE', "%{$value}%");
                });
            });
        });
        // get users from query builder
        $books = QueryBuilder::for(Book::class)
            ->with([
                'productRelated',
                'categories',
                'volume',
                'volumes',
                'producer',
                'creators'=>function($creator){
                    $creator->with('types');
                },
                'attributeValues'=>function($value){
                    $value->with('attribute');
                },
                'media'
            ])
            ->defaultSort('created_at')
            ->allowedSorts([
            ])
            ->allowedIncludes([
            ])
            ->allowedFilters([
                'volume.title',
                AllowedFilter::exact('categories.id'),
                AllowedFilter::exact('producer_id'),
                AllowedFilter::exact('creators.id'),
                AllowedFilter::exact('collections.id'),
                AllowedFilter::exact('awards.id'),
                AllowedFilter::exact('attributeValues.id'),
                'title',
                $globalSearch])
            ->paginate($request->has('per_page') !== null ?$request->get('per_page'): 15)
            ->withQueryString();
        return new BookResourceCollection($books);
    }
    public function show(Book $book): BookResource
    {
//        if(is_int($book)){
//            $book = Book::query()->where('id',$book)->firstOrFail();
//        } else {
//            $book = Book::query()->where('slug',$book)->firstOrFail();
//        }
        return BookResource::make($book->load([
            'volume',
            'volumes',
            'producer',
            'creators'=>function($creator){
                $creator->with('types');
            },
            'attributeValues'=>function($value){
                $value->with('attribute');
            },
            'media'
        ]));
    }

    public function filters()
    {
        $awards = \App\Models\Award::query()->pluck('id','title')->toArray();
        $attributes = \App\Models\Attribute::query()->with('values')->get()->pluck('values')->flatten()->groupBy('name');
        $categories = \App\Models\Category::query()->pluck('id','title')->toArray();
        $creators = \App\Models\Creator::query()->pluck('id','title')->toArray();
        $collections = \App\Models\Collection::query()->pluck('id','title')->toArray();
        $producers = \App\Models\Producer::query()->pluck('id','title')->toArray();
        return response()->json([
            'filter'=> [
                'attributes'=> [
                    'key'=> 'attributeValues.id',
                    'icon'=>''
                    'value'=> $attributes,
                ],
                'awards'=> [
                    'key'=> 'awards.id',
                    'icon'=>''
                    'value'=> $awards,
                ],
                'creators' => [
                    'key'=> 'creators.id',
                    'icon'=>''
                    'value'=> $creators,
                ],
                'categories'=> [
                    'key'=> 'categories.id',
                    'icon'=>''
                    'value'=> $categories,
                ],
                'collections'=> [
                    'key'=> 'collections.id',
                    'icon'=>''
                    'value'=> $collections,
                ],
                'producers'=> [
                    'key'=> 'producer_id',
                    'icon'=>''
                    'value'=> $producers,
                ]
            ]
        ]);
    }
}
