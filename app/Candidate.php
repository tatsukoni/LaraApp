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

    public function attend() {
        return $this->hasOne('App\Attend', 'candidateId', 'candidateId');
    }
}
