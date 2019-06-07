<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attend extends Model
{
    //
    protected $fillable = [
        'userId',
        'attend',
        'scheduleId'
    ];

    protected $primaryKey = 'candidateId';

    public $timestamps = false;

    public function candidate() {
        return $this->belongsTo('App\Candidate', 'candidateId', 'candidateId');
    }

    public function user() {
        return $this->belongsTo('App\User', 'userId');
    }
}
