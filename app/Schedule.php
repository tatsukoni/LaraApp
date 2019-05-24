<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Schedule extends Model
{
    //
    public $incrementing = false;

    protected $primaryKey = 'scheduleId';

    protected $keyType = 'string';
     
    //protected static function boot()
    //{
        //parent::boot();
     
        //static::creating(function ($model) {
            //$model->{$model->getKeyName()} = Uuid::generate()->string;
        //});
    public function  Candidates() {
        return $this->hasMany('App\Schedule');
    }
}
