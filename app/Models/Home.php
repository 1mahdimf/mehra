<?php

namespace App\Models;

use App\Enums\SettingSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Setting
{
    protected $table = 'settings';
    protected $appends = ['json'];
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function newQuery($excludeDeleted = true): \Illuminate\Database\Eloquent\Builder
    {
        return parent::newQuery()->whereSection(SettingSection::HOME);
    }
    public function model()
    {
        $this->morphTo();
    }
    private function jsonModelFormat(){
        $modelName = config('morphmap')[$this->attributes['model']];
        $model = (new $modelName);
        $values = null;
        foreach ((array)json_decode($this->attributes['value'],true) as $value){
            $values[] = $model->find($value);
        }
        return $values;
    }
    public function getJsonAttribute()
    {
        if(!is_null($this->attributes['value']) && $this->attributes['value']!='') {
            if(!is_null($this->attributes['model'])){
                $values = $this->jsonModelFormat();
            } else {
                $values = (array)json_decode($this->attributes['value'],true);
            }
            $key = is_null($this->attributes['model']) ? $this->attributes['key'] : $this->attributes['model'];
            return [
                $key => $values
            ];
        }
        return [];
    }
}
