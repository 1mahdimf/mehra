<?php

namespace App\Models;

use App\Enums\ProductRelatedType;
use App\Enums\ProductStructure;
use App\Services\CartService;
use App\Services\Media\HasMediaTrait;
use App\Services\Media\Media;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements HasMedia
{
    use HasFactory, HasMediaTrait;
    protected $guarded = [''];
    protected $appends = ['main_price','max_purchases_per_user','is_liked'];

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public static function getValidCollections(): array
    {
        return [
            'main_image',
            'gallery',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->useDisk(config('media-library.disk_name'))->singleFile();
        $this->addMediaCollection('gallery')->useDisk(config('media-library.disk_name'));
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
//        $conversion = $this->addMediaConversion('thumbnail');
//
//        $crop = $media->getCustomProperty('crop');
//
//        if (!empty($crop)) {
//            $conversion->manualCrop($crop['width'], $crop['height'], $crop['left'], $crop['top']);
//        }
//
//        $conversion->nonQueued()->performOnCollections('main_image');
    }

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function productRelated(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class,'product_related', 'product_id')
            ->using(ProductRelated::class)
            ->withPivot(['order','type']);
    }

    public function related(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        if($this->productRelated()->exists())
            return $this->productRelated()->where('pivot.type',ProductRelatedType::RELATED);

            return $this->productRelated();
    }

    public function upsell(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        if($this->productRelated()->exists())
            return $this->productRelated()->where('pivot.type',ProductRelatedType::UPSELL);

        return $this->productRelated();
    }

    public function cross_sell(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        if($this->productRelated()->exists())
            return $this->productRelated()->where('pivot.type',ProductRelatedType::CROSS_SELL);

        return $this->productRelated();
    }

    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }

    public function groups()
    {
        return $this->belongsToMany(ProductGroup::class,'product_product_groups','product_id');
    }

    public function creators()
    {
        return $this->belongsToMany(Creator::class,'creator_product','product_id')
            ->using(CreatorProduct::class)
            ->withPivot('creator_creator_type_id');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class , 'collection_product', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_product', 'product_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class,'attribute_value_product','product_id')->with('attribute');
    }
    public function scopeAttributeValueWithName($query)
    {
        $this->with('attributeValues');
        $attributeValues = $this->attributeValues()->get();
        foreach ($attributeValues as $key=> $attributeValue) {
           $attributeValue['name'] =  $attributeValue->name;
        }
        $this->attributes['attributes'] = $attributeValues;
        return $this;
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'award_product','product_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'product_id');
    }

    public function rank_attributes()
    {
        return $this->belongsToMany(RankAttribute::class, 'ranks')
            ->using(Rank::class)
            ->withPivot(['comment_id','user_id','rank_attribute_id','product_id','score']);
    }

    public function getRankAttribute()
    {
        if($this->rank_attributes()->exists())
            return [
                'rank'=>round($this->rank_attributes()->avg('score'),2),
                'number_of_voters'=> $this->rank_attributes()->get()->unique('user_id')->count()
            ];

        return null;
    }

    public function getMaxPurchasesPerUserAttribute()
    {
        $max = $this->attributes['max_purchases_per_user'];
        $cartService = new CartService();
        $cart = $cartService->getCart();
        if($cart && count($cart->items)){
            $item = $cartService->findCartItemByProductID($this->attributes['id']);
            if($item){
                if($max>0)
                    $max -= $item->quantity;
            }
        }
        return $max;
    }

    public function getMainPriceAttribute()
    {
        return $this->attributes['sale_price'] ?: $this->attributes['price'];
    }

    public function getSatisfiedNoAttribute()
    {
        $i_suggest = 0;
        foreach ($this->comments->groupBy('user_id') as $comment){
            if(in_array(1,$comment->pluck('i_suggest')->toArray())){
                $i_suggest += 1;
            }
        }
        return $i_suggest;
    }

    public function getIsLikedAttribute()
    {
        return auth()->check() ? auth()->user()->wishlist()->where('model_type','product')->where('model_id',$this->attributes['id'])->exists() : false;
    }

    /**
     * Get the settings.
     */
    public function home()
    {
        return $this->morphOne(Home::class, 'model');
    }
}
