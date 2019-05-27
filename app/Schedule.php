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
    public function candidates() {
        return $this->hasMany('App\Candidate', 'scheduleId', 'scheduleId');
    }

    public function attends() {
        return $this->hasMany('App\Attend', 'scheduleId', 'scheduleId');
    }

    public function user() {
        return $this->belongsTo('App\User', 'createdBy');
    }
}
