<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'comment'
    ];

    protected $primaryKey = 'scheduleId';

    public function schedule() {
        return $this->belongsTo('App\Schedule', 'scheduleId', 'scheduleId');
    }
}
