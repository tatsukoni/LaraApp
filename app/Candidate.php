<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $primaryKey = 'candidateId';
    //
    public function schedule() {
        return $this->belongsTo('App\Schedule');
    }

    public function attends() {
        return $this->hasMany('App\Attend', 'candidateId', 'candidateId');
    }
}
